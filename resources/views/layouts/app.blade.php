<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Apartmán')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen">
        @include('partials.header')
    <main>
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>
</div>

@livewireScripts
</body>

<div id="cookie-banner"
     class="fixed inset-x-0 bottom-0 z-[999] hidden border-t bg-white/95 backdrop-blur p-4">
    <div class="container mx-auto flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="text-sm text-slate-700">
            Používáme cookies pro provoz webu a měření návštěvnosti. Analytiku spustíme až po souhlasu.
            <a href="/cookies" class="underline text-slate-900">Více informací</a>
        </div>

        <div class="flex gap-2">
            <button id="cookie-deny"
                    class="rounded-lg border px-4 py-2 text-sm">
                Odmítnout
            </button>
            <button id="cookie-accept"
                    class="rounded-lg bg-[var(--accent)] px-4 py-2 text-sm text-white">
                Přijmout
            </button>
        </div>
    </div>
</div>

</html>
