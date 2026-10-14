@php
    $is = fn ($name) => request()->routeIs($name);
    $href = fn ($route) => \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#';

    $items = [
//        ['name' => 'Přehled',   'route' => 'admin.dashboard',    'icon' => 'home'],
        ['name' => 'Rezervace', 'route' => 'admin.reservations', 'icon' => 'calendar'],
//        ['name' => 'Fotky',     'route' => 'admin.gallery',      'icon' => 'image'],
//        ['name' => 'Ceník',     'route' => 'admin.pricing',      'icon' => 'tag'],
//        ['name' => 'Historie',  'route' => 'admin.history',      'icon' => 'history'],
    ];
@endphp

<nav class="h-full w-60 bg-neutral-50 border-r border-neutral-200">
    <div class="px-3 py-3">
        <div class="h-10 flex items-center gap-2">
            <div class="h-6 w-6 rounded bg-neutral-900"></div>
            <div class="text-sm font-semibold text-neutral-800">Admin</div>
        </div>
    </div>

    <ul class="px-2 pb-3 space-y-1">
        @foreach($items as $item)
            @php $active = $is($item['route']); @endphp
            <li>
                <a href="{{ $href($item['route']) }}"
                   class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm
                          {{ $active ? 'bg-emerald-50 text-neutral-900' : 'text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900' }}">
                    <span class="absolute right-0 top-0 h-full w-1 {{ $active ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-neutral-300' }}"></span>
                    <span class="shrink-0">@include('partials.admin.sidebar-icons.' . $item['icon'], ['active' => $active])</span>
                    <span class="truncate">{{ $item['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

</nav>
