<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administrace')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-dvh bg-neutral-100 text-neutral-900">
    <div class="min-h-dvh flex">
        <aside class="w-60 bg-neutral-50 border-r border-neutral-200">   {{-- už ne h-full, obal flex to vyřeší --}}
            @include('partials.admin.sidebar')
        </aside>

        <div class="flex-1 min-w-0 flex flex-col">
            <header class="h-12 bg-white border-b border-neutral-200 flex items-center justify-between px-4">
                <h1 class="font-semibold text-sm">@yield('title', 'Administrace')</h1>
                @if (Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-neutral-600 hover:text-neutral-900">Odhlásit</button>
                    </form>
                @endif
            </header>

            <main class="p-4 flex-1">
                @yield('content')
            </main>
        </div>
    </div>
@livewireScripts
@stack('scripts')
</body>
</html>