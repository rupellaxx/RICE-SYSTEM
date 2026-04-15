<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Rice;
use App\Models\Payment;

class OrderItemController extends Controller
{
    public function index()
    {
        $items = OrderItem::with('rice', 'order')->get();
        return view('order_items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rice_id' => 'required|exists:rices,id',
            'quantity' => 'required|numeric|min:0.1'
        ]);

        $rice = Rice::findOrFail($request->rice_id);
        $total = $rice->price * $request->quantity;

        OrderItem::create([
            'order_id' => $request->order_id,
            'rice_id' => $rice->id,
            'quantity' => $request->quantity,
            'price' => $rice->price,
            'total' => $total
        ]);

        $this->updateOrderTotal($request->order_id);

        return redirect()->back()->with('success', 'Item added!');
    }

    public function update(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);

        $request->validate([
            'quantity' => 'required|numeric|min:0.1'
        ]);

        $rice = $item->rice;
        $total = $rice->price * $request->quantity;

        $item->update([
            'quantity' => $request->quantity,
            'total' => $total
        ]);

        $this->updateOrderTotal($item->order_id);

        return redirect()->back()->with('success', 'Item updated!');
    }

    public function destroy($id)
    {
        $item = OrderItem::findOrFail($id);
        $orderId = $item->order_id;

        $item->delete();

        $this->updateOrderTotal($orderId);

        return redirect()->back()->with('success', 'Item removed!');
    }

    private function updateOrderTotal($orderId)
    {
        $order = Order::findOrFail($orderId);

        $total = $order->items()->sum('total');

        $order->update([
            'total_amount' => $total
        ]);
    }
}

