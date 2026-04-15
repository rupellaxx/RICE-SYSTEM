<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Rice;
use App\Models\Payment;

class RiceController extends Controller
{
    public function index() {
        $ricemenu = Rice::all();
        return view('rice.index', compact('ricemenu'));
    }

    public function store(Request $request) {
        Rice::create($request->all());
        return redirect()->back();
    }

    public function update(Request $request, $id) {
        $rice = Rice::findOrFail($id);
        $rice->update($request->all());
        return redirect()->back();
    }

    public function destroy($id) {
        Rice::destroy($id);
        return redirect()->back();
    }
}
