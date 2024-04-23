<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class SideMenu extends Component
{
    public $open = false;
    
    #[On('side_menu_toggle')]
    public function toggle($state)
    {
        $this->open = $state;
    }
    
    public function render()
    {
        return <<<'HTML'
        <div>
            @if ($open)
                <aside class="w-64 bg-white p-4 fixed left-0 top-20 flex flex-col z-50 h-full shadow"
                    wire:transition.slide.left.200ms="{{ $open ? 'enter' : 'leave' }}">
                    <a href="{{ route('pages.index') }}">メモ一覧</a>
                    <a href="{{ route('pages.trashed') }}">ゴミ箱</a>
                </aside>
            @endif
        </div>
        HTML;
    }
}
