<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Rice;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'status' => 'paid'
        ]);

        return redirect()->back();
    }
}
