<div x-data="{ open: false }" x-init="open = false">
    
    <style>
    [x-cloak] {
        display: none !important;
    }
    
    .sidebar {
        position: fixed;
        top: 78px;
        left: 0;
        width: 300px;
        height: calc(100% - 78px);
        z-index: 1000;
        transform: translateX(-100%);
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        padding: 10px;
    }
    
    .grid-item {
        background-color: #f1f1f1;
        padding: 20px;
        text-align: center;
        border-radius: 4px;
    }
    </style>
    
    <div class="flex justify-between">
        <div class="flex justify-between items-center">
            
            <!-- サイドメニューを開くボタン -->
            <div class="flex items-center">
                <button x-on:click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <span x-show="!open" class="material-symbols-outlined">menu</span>
                    <span x-show="open" x-cloak class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <!-- サイドメニュー -->
            <aside x-show="open" :class="{ 'open': open }" class="sidebar"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-full">
                <div class="grid-container">
                    <a href="{{ route('boards.index') }}" class="grid-item">ボード</a>
                    <a href="{{ route('pages.index') }}" class="grid-item">メモ</a>
                    <a href="{{ route('boards.trashed') }}" class="grid-item">ゴミ箱</a>
                </div>
            </aside>
            
            <!-- ヘッダーに名前を表示 -->
            <h1 class="text-xl font-bold">{{ $title ?? 'ボード' }}</h1>
        </div>
        
        <!-- ユーザーの設定メニュー -->
        <div class="flex items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
        
                        <div class="ms-1">
                            <span class="material-symbols-outlined">
                                arrow_drop_down
                            </span>
                        </div>
                    </button>
                </x-slot>
        
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>
        
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
        
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>