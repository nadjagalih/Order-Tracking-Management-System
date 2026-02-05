<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['creator', 'progress', 'files'])
            ->withCount(['progress', 'files']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'service_type' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_completion' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'draft';
        $validated['created_by'] = auth()->id();

        $order = Order::create($validated);

        // Create initial progress
        $order->progress()->create([
            'title' => 'Pesanan Dibuat',
            'description' => 'Pesanan telah dibuat dan menunggu untuk diproses.',
            'status' => 'info',
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Kode: ' . $order->order_code);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['progress.creator', 'files', 'creator']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'service_type' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,in_progress,review,revision,completed,cancelled',
            'estimated_completion' => 'nullable|date',
            'actual_completion' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Track status change
        $oldStatus = $order->status;
        $order->update($validated);

        // Create progress if status changed
        if ($oldStatus !== $validated['status']) {
            $statusLabels = [
                'draft' => 'Draft',
                'in_progress' => 'Sedang Dikerjakan',
                'review' => 'Review',
                'revision' => 'Revisi',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];

            $order->progress()->create([
                'title' => 'Status Diubah: ' . $statusLabels[$validated['status']],
                'description' => 'Status pesanan diubah dari "' . $statusLabels[$oldStatus] . '" menjadi "' . $statusLabels[$validated['status']] . '"',
                'status' => $validated['status'] === 'completed' ? 'success' : 'info',
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pesanan berhasil diupdate!');
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }

    /**
     * Get tracking link for sharing
     */
    public function getTrackingLink(Order $order)
    {
        $trackingUrl = route('tracking.show', $order->order_code);
        
        return response()->json([
            'success' => true,
            'tracking_url' => $trackingUrl,
            'order_code' => $order->order_code,
        ]);
    }
}