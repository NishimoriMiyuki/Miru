<?php

namespace App\Livewire;

use Livewire\Component;

class HamburgerMenu extends Component
{
    public $open = false;
    
    public function toggle()
    {
        $this->open = !$this->open;
        $this->dispatch('side_menu_toggle', $this->open);
    }
    
    public function render()
    {
        return <<<'HTML'
        <div class="flex items-center">
            <button wire:click="toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path class="{{ $this->open ? 'hidden' : 'inline-flex' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path class="{{ $this->open ? 'inline-flex' : 'hidden' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        HTML;
    }
}
