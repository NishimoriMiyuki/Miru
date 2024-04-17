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
                @if($this->open)
                    <span class="material-symbols-outlined">close</span>
                @else
                    <span class="material-symbols-outlined">menu</span>
                @endif
            </button>
        </div>
        HTML;
    }
}
