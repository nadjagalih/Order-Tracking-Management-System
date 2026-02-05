<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderTimeline extends Component
{
    public $orderCode;
    public $order;
    
    public function mount($orderCode)
    {
        $this->orderCode = $orderCode;
        $this->loadOrder();
    }
    
    public function loadOrder()
    {
        $this->order = Order::with(['progress.creator'])
            ->where('order_code', $this->orderCode)
            ->first();
            
        if (!$this->order) {
            abort(404, 'Pesanan tidak ditemukan');
        }
    }
    
    public function render()
    {
        return view('livewire.order-timeline');
    }
}
