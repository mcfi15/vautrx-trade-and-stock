@component('mail::message')
# Order Confirmation

Hello {{ $order->user->name }},

Your **{{ strtoupper($order->side) }}** order has been successfully placed.

---

### **Order Details**
- **Order ID:** {{ $order->id }}
- **Pair:** {{ $order->tradingPair->symbol }}
- **Type:** {{ strtoupper($order->type) }}
- **Side:** {{ strtoupper($order->side) }}
- **Quantity:** {{ $order->quantity }}
- **Price:** {{ $order->price ?? 'Market Price' }}
- **Stop Price:** {{ $order->stop_price ?? 'N/A' }}
- **Total:** {{ $order->total_amount }}
- **Fee:** {{ $order->fee }}
- **Status:** {{ ucfirst($order->status) }}

---

If this wasn’t you, contact support immediately.

Thanks,  
{{ config('app.name') }}
@endcomponent
