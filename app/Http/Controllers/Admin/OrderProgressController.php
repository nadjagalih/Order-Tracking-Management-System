<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderProgressController extends Controller
{
    /**
     * Store a new progress update
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,in_progress,review,revision,revision_1,revision_2,completed,cancelled',
            'update_status' => 'boolean',
        ]);

        // Update order status if changed
        if ($request->input('update_status') == '1') {
            $order->update([
                'status' => $validated['status'],
            ]);
        }

        // Create progress entry
        $order->progress()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Progress berhasil ditambahkan dan status order diperbarui!');
    }

    /**
     * Remove progress update
     */
    public function destroy(Order $order, $progressId)
    {
        $progress = $order->progress()->findOrFail($progressId);
        $progress->delete();

        return redirect()
            ->back()
            ->with('success', 'Progress berhasil dihapus!');
    }
}