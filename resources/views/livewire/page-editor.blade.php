<div class="flex flex-col" style="width: 1000px; height: 800px;">
    <style>
        .toolbar {
            display: flex;
            justify-content: flex-end;
        }
        
        .tool {
            display: inline-block;
            margin-right: 10px;
            position: relative;
        }
        
        .tool .tooltip {
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
    </style>
    
    @error('title') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
    @error('content') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
    <input type="text" wire:model.live.debounce.500ms="title" maxlength="50" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none text-3xl font-semibold font-bold pr-10" value="{{ $page->title }}">
    <textarea wire:model.live.debounce.500ms="content" rows="30" maxlength="2000" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none" autofocus>{{ $page->content }}</textarea>
    
    <div class="bg-white pt-2">
        編集日時: {{ $page->updated_at }}
    </div>
    
    <div class="toolbar">
        <div class="tool">
            <button wire:click="delete">
                <span class="material-symbols-outlined">
                    delete
                </span>
            </button>
            <div class="tooltip">
                削除
            </div>
        </div>
        <div class="tool">
            <button wire:click="toggleFavorite">
                @if($page->is_favorite)
                    <span class="material-symbols-outlined">
                        heart_check
                    </span>
                @else
                    <span class="material-symbols-outlined">
                        favorite
                    </span>
                @endif
            </button>
            <div class="tooltip">
                @if($page->is_favorite)
                    お気に入り解除
                @else
                    お気に入り登録
                @endif
            </div>
        </div>
        <div class="tool">
            <button wire:click="togglePublic" wire:confirm="{{ $page->is_public ? '非公開にしますか？':'公開しますか？' }}">
                @if($page->is_public)
                    <span class="material-symbols-outlined">
                        public
                    </span>
                @else
                    <span class="material-symbols-outlined">
                        public_off
                    </span>
                @endif
            </button>
            <div class="tooltip">
                @if($page->is_public)
                    非公開にする
                @else
                    公開する
                @endif
            </div>
        </div>
    </div>
</div>
        
@script
<script>
    document.querySelectorAll('.tool').forEach(function(tool) {
        tool.addEventListener('mouseover', function() {
            this.querySelector('.tooltip').style.display = 'block';
        });

        tool.addEventListener('mouseout', function() {
            this.querySelector('.tooltip').style.display = '';
        });
    });
</script>
@endscript