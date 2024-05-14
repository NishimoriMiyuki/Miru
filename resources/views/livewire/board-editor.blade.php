<x-slot name="header">
    <x-board-header :title="'ボード'" />
</x-slot>

<div class="content">
    
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
            word-break: break-all;
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
            word-break: break-all;
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
            font-size: 14px;
        }
        
        .textarea-field {
            border-color: transparent;
            width: 100%;
            resize: none;
            border-radius: 5px;
            overflow: hidden;
            vertical-align: top;
            font-size: 14px;
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
        .textarea-field:focus:hover,
        .input-field:focus,
        .input-field:focus:hover {
            background-color: #ffffff;
        }
        
        .input-field {
            border-color: transparent;
            width: 100%;
            border-radius: 5px;
            vertical-align: top;
            font-size: 14px;
        }
        
        .input-field:hover {
            background-color: #f2f2f2;
        }
        
        .input-field:focus {
            border-color: transparent;
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .content-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
            padding: 2px 12px;
        }
        
        .label {
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-top: 8px;
            width: 120px;
            text-align: left;
            pointer-events: none;
            vertical-align: top;
        }

        .title-field {
            font-size: 30px;
            font-weight: bold;
            line-height: 1.2;
            border-color: transparent;
            width: 100%;
            resize: none;
            overflow: hidden;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .title-field:focus {
            border-color: transparent;
            outline: none;
            box-shadow: none;
        }
        
        .level-select {
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            vertical-align: top;
            width: 30%;
            font-size: 14px;
            flex: 0 0 auto;
        }
        
        .level-select:focus {
            border-color: transparent;
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .select-box {
            padding: 0.5rem;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            flex-wrap: wrap;
        }
        
        .select-box:hover {
            background-color: #f2f2f2;
        }
        
        .tag {
            background-color: #3da9a0;
            color: white;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }
        
        .placeholder {
            padding-left: 3px;
            color: #808080;
        }
        
        .options {
            position: absolute;
            width: 100%;
            border-color: transparent;
            border-radius: 0.25rem;
            background-color: white;
            padding: 0.5rem 0.5rem 0;
            z-index: 1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .option {
            padding: 0.25rem;
            cursor: pointer;
            border-radius: 0.25rem;
            flex-grow: 1;
        }
        
        .option:hover {
            background-color: #f2f2f2;
        }
        
        .tag-creation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0.5rem;
        }
        
        .new-tag-input {
            flex-grow: 1;
            margin-right: 0.5rem;
        }
        
        .question {
            padding: 0.25rem;
            border-radius: 0.25rem;
            flex-grow: 1;
            display: flex;
            text-align: left;
            justify-content: space-between;
            align-items: center;
        }
        
        .question:hover {
            background-color: #f2f2f2;
        }
        
        .question > span {
            flex: 1;
            margin-right: 10px;
        }
        
        .questions {
            margin-top: 10px;
            padding-top: 10px;
            padding-top: 10px;
            border-top: 1px solid lightgray;
            border-bottom: 1px solid lightgray;
        }
        
        .comments {
            margin-top: 10px;
            padding-top: 10px;
            padding-top: 10px;
            border-top: 1px solid lightgray;
            border-bottom: 1px solid lightgray;
        }
        
        .comment:hover {
            background-color: #f2f2f2;
        }
        
        .comment {
            padding: 0.25rem;
            border-radius: 0.25rem;
            flex-grow: 1;
            display: flex;
            text-align: left;
            justify-content: space-between;
            align-items: center;
        }
        
        .save-button {
            background-color: #007a85;
            color: white;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        
        .save-button:hover {
            background-color: #005a60;
        }
        
        .tool {
            display: inline-block;
            margin-right: 10px;
            position: relative;
        }
        
        .tool:hover .tooltip {
            display: inline-block;
        }
        
        .tooltip {
            display: none;
            position: absolute;
            background-color: #555;
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            z-index: 1;
            top: 100%; 
            left: 50%; 
            transform: translateX(-50%);
            writing-mode: vertical-lr;
        }
        
        .block {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            text-align: start;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #4a4a4a;
            transition: all 0.15s ease-in-out;
        }
        
        .block:hover, .block:focus {
            background-color: #f2f2f2;
            outline: none;
        }
        
        .drop-down-button {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            text-align: start;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #4a4a4a;
            transition: all 0.15s ease-in-out;
        }
        
        .drop-down-button:hover, .drop-down-button:focus {
            background-color: #f2f2f2;
            outline: none;
        }
        
        .status-circle {
          display: inline-block;
          width: 12px;
          height: 12px;
          border-radius: 50%;
          margin-right: 5px;
        }
        
        .status-type {
            text-align: left;
        }
    </style>
    
    <!-- カンバンボード -->
    <div wire:sortable-group="updateTaskOrder" class="status-container">
        @foreach ($statuses as $status)
            <div wire:key="status-{{ $status->id }}" class="status-item">
                <div class="status-type" style="font-weight: bold;">
                  <span class="status-circle" style="background-color: {{ $status->color }};"></span>
                  {{ $status->type }}
                  ({{ count($boardRows[$status->id]) }})
                </div>
    
                <ul wire:sortable-group.item-group="{{ $status->id }}" wire:sortable-group.options="{ animation: 100 }">
                    @foreach ($boardRows[$status->id] as $boardRow)
                        <li wire:sortable-group.item="{{ $boardRow->id }}" wire:key="task-{{ $boardRow->id }}" wire:sortable-group.handle style="cursor: move;">
                            <button wire:click="editOpen({{ $boardRow->id }})">{{ $boardRow->title }}</button>
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button type="button">
                                        <span class="material-symbols-outlined">
                                            arrow_drop_down
                                        </span>
                                    </button>
                                </x-slot>
                                    
                                <x-slot name="content">
                                    <button wire:confirm="問題は完全に削除されます。よろしいですか？" wire:click="deleteBoardRow({{ $boardRow->id }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                        問題を削除する
                                    </button>
                                </x-slot>
                            </x-dropdown>
                        </li>
                    @endforeach
                </ul>
                <button wire:click="createBoardRow({{ $status->id }})" style="text-align: left; display: block;">＋新規</button>
            </div>
        @endforeach
    </div>
    
    <!-- BoardRowEdit -->
    <div x-cloak 
        x-show="$wire.isEditOpen" 
        x-transition:enter="transition ease-out duration-100 transform" 
        x-transition:enter-start="opacity-0 translate-y-4" 
        x-transition:enter-end="opacity-100 translate-y-0" 
        x-transition:leave="transition ease-in duration-100 transform" 
        x-transition:leave-start="opacity-100 translate-y-0" 
        x-transition:leave-end="opacity-0 translate-y-4"
        class="modal">
        <div class="modal-content">
            <form wire:submit="saveBoardRow">
                <div class="modal-header">
                    <div class="icon-group-left">
                        <button type="submit" class="save-button">
                            内容を保存
                        </button>
                    </div>
                    <div class="icon-group-right">
                        <button wire:click="editClose" type="button" class="tool">
                            <span class="material-symbols-outlined">
                                close
                            </span>
                            <span class="tooltip">閉じる</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    
                    <!-- タイトル -->
                    <div style="color: red;">@error('title') {{ $message }} @enderror</div>
                    <textarea id="title"
                        wire:ignore
                        wire:model="title"
                        x-init="$nextTick(() => resize())"
                        x-data="{
                                    resize() {
                                        $el.style.height = '0px';
                                        $el.style.height = $el.scrollHeight + 'px';
                                    }
                                }"
                        @input="$el.value = $el.value.replace(/\n/g, ''); resize();"
                        x-on:resize-textarea.window="
                            setTimeout(() => {
                                resize();
                            }, 100);
                        "
                        placeholder="タイトル"
                        class="title-field">
                    </textarea>
                    
                    <!-- 問題文 -->
                    <div class="content-container">
                        <label for="quiz_content" class="label">問題文</label>
                        <textarea id="quiz_content"
                            wire:ignore
                            wire:model="quiz_content"
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
                    
                    <!-- 解説 -->
                    <div class="content-container">
                        <label for="quiz_answer" class="label">解説</label>
                        <textarea id="quiz_answer"
                            wire:ignore
                            wire:model="quiz_answer"
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
                    
                    <!-- タグ選択＆追加 -->
                    <div class="content-container">
                        <label class="label">タグ</label>
                        <div x-data="{ 
                            open: false,
                        }" 
                            x-on:click.away="open = false"
                            class="relative w-full">
                            
                            <!-- タグとselectbox -->
                            <div x-on:click="open = !open" class="select-box">
                                @if(isset($boardRowTags) && count($boardRowTags) > 0)
                                    @foreach($boardRowTags as $boardRowTag)
                                        <span class="tag" x-on:click="$event.stopPropagation()">
                                            {{ $boardRowTag->name }}
                                            <button wire:click.prevent="removeTag({{ $boardRowTag->id }})">×</button>
                                        </span>
                                    @endforeach
                                @else
                                    <span class="placeholder">未入力</span>
                                @endif
                            </div>
                            
                            <!-- オプション -->
                            <div class="options" x-show="open">
                                @if(isset($boardTags))
                                    @foreach($boardTags as $boardTag)
                                    <div class="flex justify-between">
                                        <div class="option" wire:click="addTag({{ $boardTag->id }})">
                                            <span>{{ $boardTag->name }}</span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button type="button" class="tool">
                                                        <span class="material-symbols-outlined">
                                                            more_horiz
                                                        </span>
                                                        <span class="tooltip">アクション</span>
                                                    </button>
                                                </x-slot>
                                                    
                                                <x-slot name="content">
                                                    <button wire:click.prevent="deleteTag({{ $boardTag->id }})" class="block">
                                                        タグを削除する
                                                    </button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                        
                                    </div>
                                    @endforeach
                                @endif
                                <div class="tag-creation">
                                    <input type="text" wire:model="newTag" placeholder="新しいタグを作成" class="input-field" x-on:keydown.enter.prevent>
                                    <button type="button" x-on:click="if ($wire.newTag && $wire.newTag.trim() !== '') { $wire.createTag(); }">
                                        <span class="material-symbols-outlined">
                                            arrow_circle_up
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 難易度 -->
                    <div class="content-container">
                        <label for="difficulty_level_id" class="label">難易度</label>
                        <select id="difficulty_level_id" wire:model="difficulty_level_id" class="level-select" x-on:keydown.enter.prevent>
                            @foreach (\App\Models\DifficultyLevel::all() as $level)
                                <option value="{{ $level->id }}">{{ $level->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- 質問例 -->
                    <div class="content-container">
                        <div class="relative w-full">
                            
                            <!-- 質問 -->
                            <div class="questions">
                                <span>質問例</span>
                                @if(isset($questions))
                                    @foreach($questions as $question)
                                    <div class="flex justify-between">
                                        <div class="question">
                                            <span>{{ $question->content }}</span>
                                            <select wire:model="selectedAnswers.{{ $question->id }}" class="level-select" x-on:keydown.enter.prevent>
                                                @foreach ($answerList as $answer)
                                                    <option value="{{ $answer['number'] }}">{{ $answer['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button type="button" class="tool">
                                                        <span class="material-symbols-outlined">
                                                            more_horiz
                                                        </span>
                                                        <span class="tooltip">アクション</span>
                                                    </button>
                                                </x-slot>
                                                    
                                                <x-slot name="content">
                                                    <button wire:click.prevent="deleteQuestion({{ $question->id }})" class="block">
                                                        質問例を削除する
                                                    </button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                        
                                    </div>
                                    @endforeach
                                @endif
                                <div class="tag-creation">
                                    <input type="text" wire:model="newQuestion" placeholder="質問例を入力..." class="input-field" x-on:keydown.enter.prevent>
                                    <button type="button" wire:click="createQuestion">
                                        <span class="material-symbols-outlined">
                                            arrow_circle_up
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- コメント欄 -->
                    <div class="content-container">
                        <div class="relative w-full">
                            
                            <!-- コメント -->
                            <div class="comments">
                                <span>コメント</span>
                                @if(isset($comments))
                                    @foreach($comments as $comment)
                                    <div class="flex justify-between">
                                        <div class="comment">
                                            <span>{{ $comment->content }}</span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button type="button" class="tool">
                                                        <span class="material-symbols-outlined">
                                                            more_horiz
                                                        </span>
                                                        <span class="tooltip">アクション</span>
                                                    </button>
                                                </x-slot>
                                                    
                                                <x-slot name="content">
                                                    <button wire:click.prevent="deleteComment({{ $comment->id }})" class="block">
                                                        コメントを削除する
                                                    </button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                        
                                    </div>
                                    @endforeach
                                @endif
                                <div class="tag-creation">
                                    <input type="text" wire:model="newComment" placeholder="コメントを入力..." class="input-field" x-on:keydown.enter.prevent>
                                    <button type="button" wire:click="createComment">
                                        <span class="material-symbols-outlined">
                                            arrow_circle_up
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>