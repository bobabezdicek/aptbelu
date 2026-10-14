<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin – Přihlášení</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-neutral-100 flex items-center justify-center">
<div class="w-full max-w-sm">
    {{-- karta --}}
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-xl ring-1 ring-black/5 p-6">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-2 h-12 w-12 rounded-2xl bg-neutral-900 text-white flex items-center justify-center text-xl font-bold">
                A
            </div>
            <h1 class="text-xl font-semibold text-neutral-900">Přihlášení do administrace</h1>
            <p class="text-sm text-neutral-500">Zadej své přihlašovací údaje</p>
        </div>

        {{-- flash zprávy / chyby --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="" class="space-y-4" x-data="{ show: false }">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-neutral-700">E-mail</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-xl border border-neutral-300 bg-white px-3 py-2 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-neutral-900 focus:ring-0"
                    placeholder="jan.novak@example.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-neutral-700">Heslo</label>
                <div class="mt-1 relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        class="block w-full rounded-xl border border-neutral-300 bg-white px-3 py-2 pr-10 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-neutral-900 focus:ring-0"
                        placeholder="••••••••"
                    >
                    <button type="button"
                            class="absolute inset-y-0 right-0 px-3 text-neutral-500 hover:text-neutral-800"
                            @click="show = !show"
                            aria-label="Zobrazit/skrýt heslo">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.07.2.07.422 0 .622C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3.98 8.223A10.477 10.477 0 001.934 12C3.32 16.168 7.257 18.678 11.897 18.678c1.79 0 3.48-.36 5.003-1.01M6.62 6.62l10.76 10.76M9.88 9.88a3 3 0 004.24 4.24"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
{{--                <label class="inline-flex items-center gap-2 text-sm text-neutral-700">--}}
{{--                    <input type="checkbox" name="remember" class="rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900">--}}
{{--                    Pamatovat si mě--}}
{{--                </label>--}}

                {{-- do budoucna --}}
                @if (Route::has('password.request'))
                    <a href="" class="text-sm text-neutral-600 hover:text-neutral-900">
                        Zapomněl/a jsi heslo?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-neutral-900 px-4 py-2.5 text-white font-medium shadow hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900/30">
                Přihlásit se
            </button>
        </form>
    </div>

</div>

</body>
</html>
