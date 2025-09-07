<?php

namespace App\Livewire;

use App\Models\Antrean;
use Livewire\Component;

class NomorAntrean extends Component
{
    public $current;

    protected $listeners = ['refreshAntrean' => '$refresh'];

    public function mount()
    {
        $this->loadAntrean();
    }

    public function loadAntrean()
    {
        // Ambil antrean terbaru yang statusnya "menunggu" atau "dipanggil"
        $this->current = Antrean::whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('created_at', 'asc')
            ->first();
    }

    public function render()
    {
        return view('livewire.nomor-antrean');
    }
}