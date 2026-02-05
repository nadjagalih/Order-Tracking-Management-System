<?php

namespace App\Livewire;

use Livewire\Component;

class TrackingSearch extends Component
{
    public $orderCode = '';
    public $errorMessage = '';
    
    public function search()
    {
        $this->validate([
            'orderCode' => 'required|string',
        ], [
            'orderCode.required' => 'Mohon masukkan kode pesanan',
        ]);
        
        // Redirect ke halaman tracking
        return redirect()->route('track.show', $this->orderCode);
    }
    
    public function render()
    {
        return view('livewire.tracking-search');
    }
}
