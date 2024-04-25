<x-slot name="header">
    <x-board-header :title="'ボード'" />
</x-slot>

<div class="h-full">
    
    <style>
        [x-cloak] {
            display: none !important;
        }
        
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
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        button {
            cursor: pointer;
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
            overflow: auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            width: 80%;
            height: 90%;
            box-sizing: border-box;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background: white;
            padding: 10px;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        
        .icon-group-left,
        .icon-group-right {
            display: flex;
            gap: 10px;
        }
        
        .icon-group-left {
            justify-content: flex-start;
        }
        
        .icon-group-right {
            justify-content: flex-end;
        }
        
        .modal-body {
            width: 100%;
            padding: 50px 50px 0;
        }
        
        .input-field {
            border-color: transparent;
            width: 100%;
        }
        
        .input-field:focus {
            border-color: transparent;
            outline: none;
            box-shadow: none;
        }
        
        .textarea-field {
            border-color: transparent;
            width: 100%;
            resize: none;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .textarea-field:focus {
            border-color: transparent;
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .textarea-field:hover {
            background-color: #f2f2f2;
        }
        
        .textarea-field:focus,
        .textarea-field:focus:hover {
            background-color: #ffffff;
        }
        
        .textarea-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
            padding: 12px 12px;
        }
        
        .label {
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-top: 8px;
            width: 200px;
            text-align: left;
            pointer-events: none;
        }

        .title {
            font-size: 40px;
            font-weight: bold;
        }
    </style>
    
    <!-- カンバンボード -->
    <div wire:sortable-group="updateTaskOrder" class="status-container">
        @foreach ($statuses as $status)
            <div wire:key="status-{{ $status->id }}" class="status-item">
                <h4>{{ $status->type }}</h4>
    
                <ul wire:sortable-group.item-group="{{ $status->id }}" wire:sortable-group.options="{ animation: 100 }">
                    @foreach ($boardRows[$status->id] as $boardRow)
                        <li wire:sortable-group.item="{{ $boardRow->id }}" wire:key="task-{{ $boardRow->id }}">
                            <button wire:click="editOpen({{ $boardRow->id }})">{{ $boardRow->title }}</button>
                            <button wire:sortable-group.handle>drag</button>
                        </li>
                    @endforeach
                </ul>
                <button wire:click="createBoardRow({{ $status->id }})">＋新規</button>
            </div>
        @endforeach
    </div>
    
    <!-- BoardRowEdit -->
    <div x-cloak x-show="$wire.isEditOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="modal">
        <div class="modal-content">
            <form wire:submit="saveBoardRow">
                <div class="modal-header">
                    <div class="icon-group-left">
                        <button type="submit">
                            <span class="material-symbols-outlined">
                                save
                            </span>
                        </button>
                    </div>
                    <div class="icon-group-right">
                        <button wire:click="editClose" type="button">
                            <span class="material-symbols-outlined">
                                close
                            </span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <input type="text" class="title input-field" wire:model="title" placeholder="タイトル">
                    <div class="textarea-container">
                        <label for="quizContent" class="label">問題文</label>
                        <textarea id="quizContent"
                            wire:ignore
                            wire:model="quizContent"
                            x-init="$nextTick(() => resize())"
                            x-data="{
                                      resize() {
                                        $el.style.height = '0px';
                                        $el.style.height = $el.scrollHeight + 'px';
                                      }
                                    }"
                            @input="resize()"
                            x-on:resize-textarea.window="
                                      setTimeout(() => {
                                          resize();
                                          }, 100);
                                      "
                            placeholder="未入力"
                            class="textarea-field">
                        </textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>