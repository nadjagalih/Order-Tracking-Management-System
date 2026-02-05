<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderFileController extends Controller
{
    /**
     * Store a new file
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'file_name' => 'required|string|max:255',
            'file_url' => 'required|url|max:2000',
            'description' => 'nullable|string',
        ]);

        $order->files()->create($validated);

        // Create progress notification
        $order->progress()->create([
            'title' => 'File Baru Ditambahkan',
            'description' => 'File "' . $validated['file_name'] . '" telah ditambahkan.',
            'status' => 'success',
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'File berhasil ditambahkan!');
    }

    /**
     * Remove file
     */
    public function destroy(Order $order, $fileId)
    {
        $file = $order->files()->findOrFail($fileId);
        $file->delete();

        return redirect()
            ->back()
            ->with('success', 'File berhasil dihapus!');
    }
}