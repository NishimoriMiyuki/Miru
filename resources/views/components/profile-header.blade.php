<div x-data="{ open: false }" x-init="open = false">
    
    <div class="flex justify-between">
        <div class="flex justify-between items-center">
            <!-- ヘッダーに名前を表示 -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title ?? 'プロフィール' }}</h1>
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