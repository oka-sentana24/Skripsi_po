<?php

namespace App\Livewire;

use Livewire\Component;

class QueueModal extends Component
{
    public $record;

    // The mount method is used to initialize the $record variable
    public function mount($record)
    {
        $this->record = $record;
    }
    public function render()
    {
        return view('livewire.queue-modal');
    }
}
