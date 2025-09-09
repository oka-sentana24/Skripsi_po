<?php

namespace App\Livewire;

use App\Models\Pembayaran;
use Livewire\Component;

class InvoiceShow extends Component
{
    public Pembayaran $pembayaran;

    public function mount($id)
    {
        $this->pembayaran = Pembayaran::with(['pendaftaran', 'produk'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.invoice-show');
    }
}
