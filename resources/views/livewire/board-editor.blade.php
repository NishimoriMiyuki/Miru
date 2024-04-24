<x-slot name="header">
    <x-board-header :title="'ボード（編集）'" />
</x-slot>

<div class="h-full" x-data="{ open: false }">
    
    <style>
        .status-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 20px;
            flex-wrap: wrap;
        }
        
        .status-item {
            border-radius: 5px;
            width: 300px;
            padding: 10px;
        }
        
        .status-item h4 {
            margin-bottom: 10px;
        }
        
        .status-item ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .status-item ul li {
            background-color: #fff;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        button {
            cursor: pointer;
        }
        
        [x-cloak] {
            display: none !important;
        }
        
        .modal {
            background-color: rgba(0,0,0,0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
    
    <div wire:sortable-group="updateTaskOrder" class="status-container">
        @foreach ($statuses as $status)
            <div wire:key="status-{{ $status->id }}" class="status-item">
                <h4>{{ $status->type }}</h4>
    
                <ul wire:sortable-group.item-group="{{ $status->id }}" wire:sortable-group.options="{ animation: 100 }">
                    @foreach ($boardRows[$status->id] as $boardRow)
                        <li wire:sortable-group.item="{{ $boardRow->id }}" wire:key="task-{{ $boardRow->id }}">
                            <span x-on:click="open = true">{{ $boardRow->title }}</span>
                            <button wire:sortable-group.handle>drag</button>
                        </li>
                    @endforeach
                </ul>
                <button wire:click="createBoardRow({{ $status->id }})">＋新規</button>
            </div>
        @endforeach
    </div>
    
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="modal">
        <div class="modal-content">
            <h2>Modal Title</h2>
            <p>Modal Content...</p>
            <button x-on:click="open = false">Close</button>
        </div>
    </div>
</div>