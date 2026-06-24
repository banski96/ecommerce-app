<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center p-4 lg:p-12 bg-cover bg-center bg-no-repeat relative before:absolute before:inset-0 before:bg-gradient-to-b before:from-black/10 before:to-black/40"
            style="background-image: url('{{ asset('/assets/login-banner.png') }}');">

            <div class="flex flex-col items-center w-full sm:max-w-md relative z-10">

                <div class="w-full px-6 py-4 bg-white dark:bg-gray-800 shadow-2xl overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </body>
</html>
