<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIBSEN') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#F2F4FA] px-4 py-10">
        <div class="w-full max-w-sm">
            <!-- Kartu logo -->
            <div class="bg-white rounded-t-2xl px-6 py-8 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 object-contain">
            </div>

            <!-- Kartu form -->
            <div class="bg-white rounded-b-2xl shadow-sm px-6 py-8 border border-[#E6E9F2] border-t-0">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>