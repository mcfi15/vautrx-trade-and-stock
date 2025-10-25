<?php

namespace App\Services;

use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Ratchet\Client\Connector;
use React\EventLoop\Loop;
use React\Socket\Connector as SocketConnector;

class CryptoWebSocketService
{
    private $loop;
    private $connections = [];
    private $reconnectTimers = [];
    private $priceBuffer = [];
    private const PRICE_UPDATE_BATCH_SIZE = 10;
    private const MAX_RECONNECT_ATTEMPTS = 5;

    public function __construct()
    {
        $this->loop = Loop::get();
    }

    /**
     * Start WebSocket connections for tracked cryptocurrencies
     */
    public function start()
    {
        Log::info('🚀 Starting Crypto WebSocket Service...');

        $trackedCoins = $this->getTrackedCryptocurrencies();

        if ($trackedCoins->isEmpty()) {
            Log::warning('⚠️ No cryptocurrencies enabled for tracking. Please enable some in admin panel.');
            return;
        }

        Log::info("📊 Tracking {$trackedCoins->count()} cryptocurrencies");

        // Connect to Binance WebSocket for each cryptocurrency
        foreach ($trackedCoins as $crypto) {
            $this->connectToCrypto($crypto);
        }

        // Schedule periodic database updates
        $this->loop->addPeriodicTimer(5, function () {
            $this->flushPriceBuffer();
        });

        // Heartbeat to check connections
        $this->loop->addPeriodicTimer(30, function () {
            $this->checkConnections();
        });

        Log::info('✅ WebSocket service initialized. Starting event loop...');
        $this->loop->run();
    }

    /**
     * Connect to cryptocurrency WebSocket stream
     */
    private function connectToCrypto(Cryptocurrency $crypto, int $attempt = 1)
    {
        $symbol = strtolower($crypto->symbol);
        $streamName = "{$symbol}usdt@ticker"; // Track price against USDT

        Log::info("🔌 Connecting to {$crypto->symbol} WebSocket stream...");

        $connector = new Connector($this->loop, new SocketConnector($this->loop));

        $connector('wss://stream.binance.com:9443/ws/' . $streamName)
            ->then(
                function ($conn) use ($crypto, $symbol) {
                    Log::info("✅ Connected to {$crypto->symbol} stream");
                    $this->connections[$symbol] = $conn;

                    $conn->on('message', function ($msg) use ($crypto) {
                        $this->handleMessage($crypto, $msg);
                    });

                    $conn->on('close', function ($code = null, $reason = null) use ($crypto) {
                        Log::warning("❌ {$crypto->symbol} connection closed. Code: {$code}, Reason: {$reason}");
                        $this->scheduleReconnect($crypto);
                    });

                    $conn->on('error', function ($e) use ($crypto) {
                        Log::error("⚠️ {$crypto->symbol} WebSocket error: " . $e->getMessage());
                    });
                },
                function ($e) use ($crypto, $attempt) {
                    Log::error("❌ Failed to connect to {$crypto->symbol}: " . $e->getMessage());
                    
                    if ($attempt < self::MAX_RECONNECT_ATTEMPTS) {
                        $this->scheduleReconnect($crypto, $attempt);
                    } else {
                        Log::error("🚫 Max reconnection attempts reached for {$crypto->symbol}");
                    }
                }
            );
    }

    /**
     * Handle incoming WebSocket message
     */
    private function handleMessage(Cryptocurrency $crypto, $msg)
    {
        try {
            $data = json_decode($msg, true);

            if (!isset($data['c'])) {
                return; // Invalid message format
            }

            $currentPrice = (float) $data['c'];
            $priceChange24h = (float) $data['P'];
            $volume24h = (float) $data['v'];
            $high24h = (float) $data['h'];
            $low24h = (float) $data['l'];

            // Buffer the price update
            $this->priceBuffer[$crypto->id] = [
                'current_price' => $currentPrice,
                'price_change_24h' => $priceChange24h,
                'volume_24h' => $volume24h,
                'high_24h' => $high24h,
                'low_24h' => $low24h,
                'price_updated_at' => now(),
            ];

            // Store in cache for instant frontend access
            Cache::put("crypto_price_{$crypto->id}", [
                'symbol' => $crypto->symbol,
                'price' => $currentPrice,
                'change' => $priceChange24h,
                'volume' => $volume24h,
                'updated_at' => now()->toIso8601String(),
            ], 60);

            // Broadcast to connected clients (if using Laravel Broadcasting)
            // broadcast(new CryptoPriceUpdated($crypto, $currentPrice, $priceChange24h));

            // Log every 100th update to avoid spam
            if (rand(1, 100) === 1) {
                Log::debug("📈 {$crypto->symbol}: \${$currentPrice} ({$priceChange24h}%)");
            }

        } catch (\Exception $e) {
            Log::error("⚠️ Error processing {$crypto->symbol} message: " . $e->getMessage());
        }
    }

    /**
     * Flush price buffer to database
     */
    private function flushPriceBuffer()
    {
        if (empty($this->priceBuffer)) {
            return;
        }

        try {
            foreach ($this->priceBuffer as $cryptoId => $priceData) {
                Cryptocurrency::where('id', $cryptoId)->update($priceData);
            }

            $count = count($this->priceBuffer);
            Log::info("💾 Updated {$count} cryptocurrency prices in database");
            $this->priceBuffer = [];

        } catch (\Exception $e) {
            Log::error('⚠️ Error flushing price buffer: ' . $e->getMessage());
        }
    }

    /**
     * Schedule reconnection for a cryptocurrency
     */
    private function scheduleReconnect(Cryptocurrency $crypto, int $attempt = 1)
    {
        $symbol = strtolower($crypto->symbol);
        
        // Cancel existing reconnect timer if any
        if (isset($this->reconnectTimers[$symbol])) {
            $this->loop->cancelTimer($this->reconnectTimers[$symbol]);
        }

        $delay = min(30, pow(2, $attempt)); // Exponential backoff: 2, 4, 8, 16, 30 seconds
        
        Log::info("⏱️ Scheduling reconnect for {$crypto->symbol} in {$delay} seconds (attempt {$attempt})...");

        $this->reconnectTimers[$symbol] = $this->loop->addTimer($delay, function () use ($crypto, $attempt) {
            $this->connectToCrypto($crypto, $attempt + 1);
        });
    }

    /**
     * Check connection health
     */
    private function checkConnections()
    {
        $activeConnections = count($this->connections);
        $trackedCoins = $this->getTrackedCryptocurrencies()->count();

        Log::info("💓 Heartbeat: {$activeConnections}/{$trackedCoins} connections active");

        if ($activeConnections < $trackedCoins) {
            Log::warning("⚠️ Some connections are down. Attempting to reconnect...");
            // Reconnection is handled automatically by the 'close' event
        }
    }

    /**
     * Get cryptocurrencies that should be tracked
     */
    private function getTrackedCryptocurrencies()
    {
        return Cryptocurrency::where('is_active', true)
            ->where('is_tradable', true)
            ->where('enable_realtime', true) // New field we'll add
            ->get();
    }

    /**
     * Stop all WebSocket connections gracefully
     */
    public function stop()
    {
        Log::info('🛑 Stopping WebSocket service...');

        // Flush remaining prices
        $this->flushPriceBuffer();

        // Close all connections
        foreach ($this->connections as $symbol => $conn) {
            $conn->close();
            Log::info("🔌 Closed {$symbol} connection");
        }

        // Cancel all timers
        foreach ($this->reconnectTimers as $timer) {
            $this->loop->cancelTimer($timer);
        }

        $this->loop->stop();
        Log::info('✅ WebSocket service stopped');
    }
}
