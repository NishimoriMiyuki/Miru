<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 

class Toaster extends Component
{
    public $message = '';

    #[On('toaster')]
    public function showMessage($message)
    {
        $this->message = $message;
    }

    public function render()
    {
        return view('livewire.toaster');
    }
}