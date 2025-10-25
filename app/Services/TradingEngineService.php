<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Trade;
use App\Models\TradingPair;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TradingEngineService
{
    private $blockchainService;

    public function __construct(BlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }

    /**
     * Place a new order
     */
    public function placeOrder($userId, $tradingPairId, $type, $side, $quantity, $price = null, $stopPrice = null)
    {
        try {
            DB::beginTransaction();

            $tradingPair = TradingPair::with(['baseCurrency', 'quoteCurrency'])->findOrFail($tradingPairId);
            
            if (!$tradingPair->is_active) {
                throw new \Exception('Trading pair is not active');
            }

            // Validate order parameters
            $this->validateOrder($tradingPair, $type, $side, $quantity, $price);

            // Calculate price for market orders
            if ($type === 'market') {
                $price = $tradingPair->getCurrentPrice();
            }

            $totalAmount = bcmul($quantity, $price, 8);
            $fee = bcmul($totalAmount, $tradingPair->trading_fee, 8);

            // Get user wallets
            $user = \App\Models\User::findOrFail($userId);
            
            if ($side === 'buy') {
                // Buying: need quote currency (e.g., USDT)
                $wallet = $user->getOrCreateWallet($tradingPair->quote_currency_id);
                $requiredAmount = bcadd($totalAmount, $fee, 8);
                
                if ($wallet->availableBalance < $requiredAmount) {
                    throw new \Exception('Insufficient balance');
                }
                
                // Lock the funds
                $wallet->lockBalance($requiredAmount);
            } else {
                // Selling: need base currency (e.g., BTC)
                $wallet = $user->getOrCreateWallet($tradingPair->base_currency_id);
                
                if ($wallet->availableBalance < $quantity) {
                    throw new \Exception('Insufficient balance');
                }
                
                // Lock the crypto
                $wallet->lockBalance($quantity);
            }

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'trading_pair_id' => $tradingPairId,
                'type' => $type,
                'side' => $side,
                'price' => $price,
                'stop_price' => $stopPrice,
                'quantity' => $quantity,
                'remaining_quantity' => $quantity,
                'total_amount' => $totalAmount,
                'fee' => $fee,
                'status' => 'pending',
            ]);

            // Execute market orders immediately
            if ($type === 'market') {
                $this->executeMarketOrder($order);
            } else {
                // Try to match limit orders
                $this->matchLimitOrder($order);
            }

            DB::commit();

            return $order->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Place Order Error', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'trading_pair_id' => $tradingPairId,
            ]);
            throw $e;
        }
    }

    /**
     * Execute a market order
     */
    private function executeMarketOrder($order)
    {
        try {
            $tradingPair = $order->tradingPair;
            $user = $order->user;

            if ($order->side === 'buy') {
                // Execute buy order
                $quoteWallet = $user->getWallet($tradingPair->quote_currency_id);
                $baseWallet = $user->getOrCreateWallet($tradingPair->base_currency_id);

                // Unlock and deduct quote currency
                $totalCost = bcadd($order->total_amount, $order->fee, 8);
                $quoteWallet->unlockBalance($totalCost);
                $quoteWallet->subtractBalance($totalCost);

                // Add base currency
                $baseWallet->addBalance($order->quantity);
            } else {
                // Execute sell order
                $baseWallet = $user->getWallet($tradingPair->base_currency_id);
                $quoteWallet = $user->getOrCreateWallet($tradingPair->quote_currency_id);

                // Unlock and deduct base currency
                $baseWallet->unlockBalance($order->quantity);
                $baseWallet->subtractBalance($order->quantity);

                // Add quote currency (minus fee)
                $netAmount = bcsub($order->total_amount, $order->fee, 8);
                $quoteWallet->addBalance($netAmount);
            }

            // Update order status
            $order->update([
                'filled_quantity' => $order->quantity,
                'remaining_quantity' => 0,
                'status' => 'completed',
                'executed_at' => now(),
            ]);

            // Create trade record
            $this->createTradeRecord($order);

            // Create transaction records
            $this->createTransactionRecords($order);

        } catch (\Exception $e) {
            Log::error('Execute Market Order Error', [
                'message' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
            throw $e;
        }
    }

    /**
     * Match limit order with existing orders
     */
    private function matchLimitOrder($order)
    {
        try {
            $tradingPair = $order->tradingPair;
            
            // Find matching orders from the opposite side
            $matchingOrders = Order::where('trading_pair_id', $tradingPair->id)
                ->where('side', $order->side === 'buy' ? 'sell' : 'buy')
                ->where('type', 'limit')
                ->where('status', 'pending')
                ->where('remaining_quantity', '>', 0)
                ->when($order->side === 'buy', function ($query) use ($order) {
                    // For buy orders, match sell orders with price <= our buy price
                    return $query->where('price', '<=', $order->price)
                        ->orderBy('price', 'asc');
                }, function ($query) use ($order) {
                    // For sell orders, match buy orders with price >= our sell price
                    return $query->where('price', '>=', $order->price)
                        ->orderBy('price', 'desc');
                })
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($matchingOrders as $matchingOrder) {
                if ($order->remaining_quantity <= 0) {
                    break;
                }

                $this->matchOrders($order, $matchingOrder);
            }

            // Update order status
            if ($order->remaining_quantity <= 0) {
                $order->update(['status' => 'completed', 'executed_at' => now()]);
            } elseif ($order->filled_quantity > 0) {
                $order->update(['status' => 'partial']);
            }

        } catch (\Exception $e) {
            Log::error('Match Limit Order Error', [
                'message' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
        }
    }

    /**
     * Match two orders
     */
    private function matchOrders($order1, $order2)
    {
        try {
            $matchQuantity = min($order1->remaining_quantity, $order2->remaining_quantity);
            $matchPrice = $order2->price; // Use the maker's price

            $buyOrder = $order1->side === 'buy' ? $order1 : $order2;
            $sellOrder = $order1->side === 'sell' ? $order1 : $order2;

            $tradingPair = $order1->tradingPair;
            $totalAmount = bcmul($matchQuantity, $matchPrice, 8);
            
            $buyerFee = bcmul($totalAmount, $tradingPair->trading_fee, 8);
            $sellerFee = bcmul($matchQuantity, $tradingPair->trading_fee, 18);

            // Update buyer's wallets
            $buyerQuoteWallet = $buyOrder->user->getWallet($tradingPair->quote_currency_id);
            $buyerBaseWallet = $buyOrder->user->getOrCreateWallet($tradingPair->base_currency_id);
            
            $buyerCost = bcadd($totalAmount, $buyerFee, 8);
            $buyerQuoteWallet->unlockBalance($buyerCost);
            $buyerQuoteWallet->subtractBalance($buyerCost);
            $buyerBaseWallet->addBalance($matchQuantity);

            // Update seller's wallets
            $sellerBaseWallet = $sellOrder->user->getWallet($tradingPair->base_currency_id);
            $sellerQuoteWallet = $sellOrder->user->getOrCreateWallet($tradingPair->quote_currency_id);
            
            $sellerBaseWallet->unlockBalance($matchQuantity);
            $sellerBaseWallet->subtractBalance($matchQuantity);
            
            $sellerRevenue = bcsub($totalAmount, bcmul($totalAmount, $tradingPair->trading_fee, 8), 8);
            $sellerQuoteWallet->addBalance($sellerRevenue);

            // Update orders
            $order1->filled_quantity = bcadd($order1->filled_quantity, $matchQuantity, 18);
            $order1->remaining_quantity = bcsub($order1->remaining_quantity, $matchQuantity, 18);
            $order1->save();

            $order2->filled_quantity = bcadd($order2->filled_quantity, $matchQuantity, 18);
            $order2->remaining_quantity = bcsub($order2->remaining_quantity, $matchQuantity, 18);
            $order2->save();

            // Create trade record
            Trade::create([
                'order_id' => $buyOrder->id,
                'trading_pair_id' => $tradingPair->id,
                'buyer_id' => $buyOrder->user_id,
                'seller_id' => $sellOrder->user_id,
                'price' => $matchPrice,
                'quantity' => $matchQuantity,
                'total_amount' => $totalAmount,
                'buyer_fee' => $buyerFee,
                'seller_fee' => $sellerFee,
                'blockchain_status' => 'pending',
            ]);

        } catch (\Exception $e) {
            Log::error('Match Orders Error', [
                'message' => $e->getMessage(),
                'order1_id' => $order1->id,
                'order2_id' => $order2->id,
            ]);
            throw $e;
        }
    }

    /**
     * Create trade record for market order
     */
    private function createTradeRecord($order)
    {
        $tradingPair = $order->tradingPair;
        
        Trade::create([
            'order_id' => $order->id,
            'trading_pair_id' => $tradingPair->id,
            'buyer_id' => $order->side === 'buy' ? $order->user_id : 1, // Platform as counterparty
            'seller_id' => $order->side === 'sell' ? $order->user_id : 1,
            'price' => $order->price,
            'quantity' => $order->quantity,
            'total_amount' => $order->total_amount,
            'buyer_fee' => $order->side === 'buy' ? $order->fee : 0,
            'seller_fee' => $order->side === 'sell' ? $order->fee : 0,
            'blockchain_status' => 'pending',
        ]);
    }

    /**
     * Create transaction records
     */
    private function createTransactionRecords($order)
    {
        $tradingPair = $order->tradingPair;
        $user = $order->user;

        if ($order->side === 'buy') {
            // Quote currency transaction (USDT out)
            $quoteWallet = $user->getWallet($tradingPair->quote_currency_id);
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $tradingPair->quote_currency_id,
                'type' => 'trade_buy',
                'amount' => $order->total_amount,
                'fee' => $order->fee,
                'balance_before' => bcadd($quoteWallet->balance, bcadd($order->total_amount, $order->fee, 8), 18),
                'balance_after' => $quoteWallet->balance,
                'status' => 'completed',
                'description' => "Buy {$order->quantity} {$tradingPair->baseCurrency->symbol}",
            ]);

            // Base currency transaction (BTC in)
            $baseWallet = $user->getWallet($tradingPair->base_currency_id);
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $tradingPair->base_currency_id,
                'type' => 'trade_buy',
                'amount' => $order->quantity,
                'fee' => 0,
                'balance_before' => bcsub($baseWallet->balance, $order->quantity, 18),
                'balance_after' => $baseWallet->balance,
                'status' => 'completed',
                'description' => "Received {$order->quantity} {$tradingPair->baseCurrency->symbol}",
            ]);
        } else {
            // Similar logic for sell orders
            $baseWallet = $user->getWallet($tradingPair->base_currency_id);
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $tradingPair->base_currency_id,
                'type' => 'trade_sell',
                'amount' => $order->quantity,
                'fee' => 0,
                'balance_before' => bcadd($baseWallet->balance, $order->quantity, 18),
                'balance_after' => $baseWallet->balance,
                'status' => 'completed',
                'description' => "Sold {$order->quantity} {$tradingPair->baseCurrency->symbol}",
            ]);
        }
    }

    /**
     * Cancel an order
     */
    public function cancelOrder($orderId, $userId)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->whereIn('status', ['pending', 'partial'])
                ->firstOrFail();

            $tradingPair = $order->tradingPair;
            $user = $order->user;

            // Unlock funds
            if ($order->side === 'buy') {
                $wallet = $user->getWallet($tradingPair->quote_currency_id);
                $lockedAmount = bcmul($order->remaining_quantity, $order->price, 8);
                $lockedAmount = bcadd($lockedAmount, bcmul($lockedAmount, $tradingPair->trading_fee, 8), 8);
                $wallet->unlockBalance($lockedAmount);
            } else {
                $wallet = $user->getWallet($tradingPair->base_currency_id);
                $wallet->unlockBalance($order->remaining_quantity);
            }

            $order->cancel();

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel Order Error', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            throw $e;
        }
    }

    /**
     * Validate order parameters
     */
    private function validateOrder($tradingPair, $type, $side, $quantity, $price)
    {
        if (!in_array($type, ['market', 'limit', 'stop_loss', 'stop_limit'])) {
            throw new \Exception('Invalid order type');
        }

        if (!in_array($side, ['buy', 'sell'])) {
            throw new \Exception('Invalid order side');
        }

        if ($quantity <= 0) {
            throw new \Exception('Quantity must be greater than 0');
        }

        if (in_array($type, ['limit', 'stop_limit']) && (!$price || $price <= 0)) {
            throw new \Exception('Price is required for limit orders');
        }

        $totalAmount = bcmul($quantity, $price ?? $tradingPair->getCurrentPrice(), 8);

        if ($totalAmount < $tradingPair->min_trade_amount) {
            throw new \Exception("Minimum trade amount is {$tradingPair->min_trade_amount}");
        }

        if ($totalAmount > $tradingPair->max_trade_amount) {
            throw new \Exception("Maximum trade amount is {$tradingPair->max_trade_amount}");
        }
    }
}
