<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Show tracking page for client
     */
    public function show($orderCode)
    {
        $order = Order::with(['progress.creator', 'files'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('tracking.show', compact('order'));
    }

    /**
     * Show tracking search page
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Search order by code
     */
    public function search(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
        ]);

        $order = Order::where('order_code', $request->order_code)->first();

        if (!$order) {
            return redirect()
                ->back()
                ->with('error', 'Kode pesanan tidak ditemukan!');
        }

        return redirect()->route('tracking.show', $order->order_code);
    }
}