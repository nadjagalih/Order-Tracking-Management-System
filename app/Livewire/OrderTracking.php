<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class OrderTracking extends Component
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
        $this->order = Order::with(['progress.creator', 'files'])
            ->where('order_code', $this->orderCode)
            ->first();
            
        if (!$this->order) {
            abort(404, 'Pesanan tidak ditemukan');
        }
    }
    
    #[On('refresh-order')]
    public function refresh()
    {
        $this->loadOrder();
    }
    
    public function render()
    {
        return view('livewire.order-tracking');
    }
}
