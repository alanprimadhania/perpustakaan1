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

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-blue-50 to-gray-100">

    <div class="min-h-screen flex flex-col">

        {{-- Navbar --}}
        @include('layouts.navigation')

        {{-- Header --}}
        @isset($header)
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto py-5 px-6">
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ $header }}
                </h1>
            </div>
        </header>
        @endisset

        {{-- Content --}}
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    {{ $slot }}
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
            © {{ date('Y') }} Perpustakaan Digital
        </footer>

    </div>

</body>
</html>