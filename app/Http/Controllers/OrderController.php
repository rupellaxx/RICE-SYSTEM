<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Rice;
use App\Models\Payment;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Filter out items with 0 quantity
        $items = collect($request->items)->filter(fn($item) => ($item['quantity'] ?? 0) > 0);

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'Please select at least one item.');
        }

        $order = Order::create([
            'user_id'      => auth()->id(),
            'total_amount' => 0,
        ]);

        $total = 0;

        foreach ($items as $item) {
            $rice      = Rice::findOrFail($item['rice_id']);
            $itemTotal = $item['quantity'] * $rice->price;

            OrderItem::create([
                'order_id' => $order->id,
                'rice_id'  => $rice->id,
                'quantity' => $item['quantity'],
                'price'    => $rice->price,
                'total'    => $itemTotal,
            ]);

            $total += $itemTotal;
        }

        $order->update(['total_amount' => $total]);

        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Order placed successfully!');
    }

    public function show($id)
    {
        $order    = Order::with('items.rice', 'payment')->findOrFail($id);
        $ricemenu = Rice::all();

        return view('orders.show', compact('order', 'ricemenu'));
    }
}