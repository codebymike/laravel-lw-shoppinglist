<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shopping Lists</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="min-h-screen flex flex-col items-center justify-center py-4 sm:pt-0">
                <div>
                    <h1 class="text-2xl font-bold">Shopping Lists</h1>
                </div>

                <div class="mt-6">
                    {{-- <livewire:shoppinglists /> --}}
                </div>
            </div>
        </div>
    </body>
</html>
