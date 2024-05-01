<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-600 pt-16">
        
        <style>
            body::-webkit-scrollbar {
                width: 10px;
            }
            
            body::-webkit-scrollbar-track {
                background: #f1f1f1; 
            }
             
            body::-webkit-scrollbar-thumb {
                background: #b8b8b8;
            }
            
            body::-webkit-scrollbar-thumb:hover {
                background: #9e9e9e;
            }
            
            .shadow {
                box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.3);
            }
        </style>
        
        <div class="min-h-screen bg-white">
            
            <!-- Page Heading -->
            @if (isset($header))
                <header x-data="{ scrolled: false }" 
                        @scroll.window="scrolled = (window.pageYOffset > 0 ? true : false)" 
                        :class="{ 'shadow': scrolled }" 
                        class="fixed top-0 w-full bg-white h-16 border-b border-gray-300 z-50">
                    <div class="p-2">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex justify-center items-center text-center pt-8">
                {{ $slot }}
            </main>
            
            <livewire:toaster />
        </div>
    </body>
</html>
