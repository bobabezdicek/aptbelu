<section id="gallery" class="section container">
    <div class="text-center mb-8">
        <h2 class="text-center text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
            Galerie <span class="text-[var(--accent)]">apartmánu</span>
        </h2>
        <p class="text-center text-slate-600 mb-10 font-semibold">
            Prohlédněte si galerii našeho apartmánu
        </p>
    </div>

    @php $rest = array_slice($images, $visible); @endphp

    {{-- první řada náhledů --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach (array_slice($images, 0, $visible) as $i => $img)
            <figure class="card overflow-hidden cursor-zoom-in" wire:click="open({{ $i }})">
                <img src="{{ asset('images/'.$img.'.jpg') }}" alt="{{ $img }}" class="w-full h-72 object-cover" loading="lazy">
            </figure>
        @endforeach
    </div>

    @if (count($rest))
        <div id="gallery-rest"
             class="mt-8 overflow-hidden transition-[max-height] duration-500 ease-in-out"
             style="max-height: {{ $expanded ? '9999px' : '0px' }};"
             aria-hidden="{{ $expanded ? 'false' : 'true' }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach ($rest as $k => $img)
                    @php $i = $visible + $k; @endphp
                    <figure class="card overflow-hidden cursor-zoom-in" wire:click="open({{ $i }})">
                        <img src="{{ asset('images/'.$img.'.jpg') }}" alt="{{ $img }}" class="w-full h-72 object-cover" loading="lazy">
                    </figure>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-8">
            <button wire:click="toggle"
                    class="btn border border-[var(--accent)] text-[var(--accent)] bg-transparent hover:bg-[var(--accent)] hover:text-white"
                    aria-expanded="{{ $expanded ? 'true' : 'false' }}">
                {{ $expanded ? 'Skrýt galerii' : 'Zobrazit celou galerii' }}
            </button>
        </div>
    @endif

    @if ($isOpen)
        <div id="lightbox"
             wire:keydown.window.escape="close"
             wire:keydown.window.arrow-right="next"
             wire:keydown.window.arrow-left="prev"
             tabindex="0"
             class="fixed inset-0 z-[100] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
             aria-modal="true" role="dialog">

            <div class="absolute inset-0" wire:click="close"></div>

            <div class="relative z-10 max-w-[min(92vw,900px)] max-h-[88vh]">
                <div class="bg-white/5 rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10">
                    <img
                        src="{{ asset('images/'.$images[$current].'.jpg') }}"
                        alt="photo {{ $current + 1 }}"
                        class="block max-h-[82vh] w-auto object-contain select-none"
                        draggable="false">
                </div>

                <div class="absolute left-1/2 -translate-x-1/2 -bottom-4 px-3 h-8 rounded-full bg-black/70 text-white text-xs grid place-items-center">
                    {{ $current + 1 }} / {{ count($images) }}
                </div>

                <button wire:click="close"
                        class="absolute -top-3 -right-3 h-9 w-9 rounded-full bg-black/70 text-white grid place-items-center hover:bg-black/80"
                        aria-label="Zavřít">✕
                </button>

                <button wire:click="prev"
                        class="absolute left-2 top-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-black/60 text-white grid place-items-center hover:bg-black/70"
                        aria-label="Předchozí">‹
                </button>
                <button wire:click="next"
                        class="absolute right-2 top-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-black/60 text-white grid place-items-center hover:bg-black/70"
                        aria-label="Další">›
                </button>
            </div>
        </div>

        <script>
            window.addEventListener('gallery-toggle', (e) => {
                const el = document.getElementById('gallery-rest');
                if (!el) return;

                const open = !!(e.detail && e.detail.expanded);

                requestAnimationFrame(() => {
                    el.style.maxHeight = open ? (el.scrollHeight + 'px') : '0px';
                });
            });

            window.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('gallery-rest');
                if (!el) return;

                const expanded = el.style.maxHeight !== '0px';
                if (expanded) el.style.maxHeight = el.scrollHeight + 'px';
            });
        </script>
    @endif
</section>
