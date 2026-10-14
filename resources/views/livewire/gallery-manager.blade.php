<div x-data="galleryPage()" x-init="init()">
    @if (session('ok'))
        <div class="mb-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
            {{ session('ok') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-4">
        {{-- Panel: Nahrání / Ořez --}}
        <section class="lg:w-80 bg-white rounded-2xl p-4 shadow-sm ring-1 ring-black/5">
            <h3 class="font-semibold">Nahrát fotku</h3>

            {{-- Dropzone --}}
            <div class="mt-3">
                <label class="block">
                    <input type="file" accept="image/*" class="hidden" x-ref="file"
                           x-on:change="handleFile($event)" />
                    <div class="border-2 border-dashed rounded-xl px-3 py-8 text-sm text-neutral-500
                                hover:bg-neutral-50 cursor-pointer text-center"
                         x-on:click="$refs.file.click()">
                        Přetáhni sem obrázek nebo klikni pro výběr
                    </div>
                </label>
            </div>

            <template x-if="previewUrl">
                <div class="mt-3">
                    <div class="text-xs text-neutral-500 mb-1">Náhled</div>
                    <img :src="previewUrl" class="w-full rounded-lg border" alt="">
                    <div class="flex gap-2 mt-3">
                        <button class="px-3 py-2 rounded-lg border hover:bg-neutral-50 text-sm"
                                x-on:click="openCropper(previewUrl)">Oříznout</button>
                        <button class="px-3 py-2 rounded-lg bg-neutral-900 text-white text-sm"
                                x-on:click="emitCropped(previewUrl)">Nahrát bez ořezu</button>
                    </div>
                </div>
            </template>

            {{-- uložení ořezu přijde sem jako Livewire volání saveCropped() --}}
        </section>

        {{-- Grid fotek --}}
        <section class="flex-1 bg-white rounded-2xl p-4 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Galerie</h3>
            </div>

            <div class="mt-4">
                <ul id="gallery-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @forelse($photos as $p)
                        <li class="group relative rounded-xl overflow-hidden border bg-neutral-50"
                            data-id="{{ $p->id }}">
                            <img src="{{ $p->thumb_url }}" alt="" class="w-full h-36 object-cover select-none pointer-events-none">
                            <div class="absolute inset-0 flex items-start justify-between p-2 opacity-0 group-hover:opacity-100 transition">
                                <button class="px-2 py-1 text-xs rounded bg-white/80 hover:bg-white"
                                        wire:click="startCrop({{ $p->id }})">Oříznout</button>
                                <button class="px-2 py-1 text-xs rounded bg-white/80 text-red-600 hover:bg-white"
                                        wire:click="delete({{ $p->id }})">Smazat</button>
                            </div>
                            <div class="absolute bottom-1 left-1 text-[10px] text-neutral-700 bg-white/80 rounded px-1">
                                #{{ $p->sort }}
                            </div>
                        </li>
                    @empty
                        <p class="text-sm text-neutral-500">Zatím žádné fotky.</p>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>

    {{-- Modal pro cropper --}}
    <div x-cloak x-show="cropOpen"
         class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-3xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Oříznout</h3>
                <button class="text-neutral-600 hover:text-neutral-900" x-on:click="closeCropper()">Zavřít</button>
            </div>

            <div class="aspect-video w-full border rounded-lg overflow-hidden">
                <img x-ref="cropImage" alt="">
            </div>

            <div class="mt-3 flex justify-end gap-2">
                <button class="px-3 py-2 rounded-lg border hover:bg-neutral-50" x-on:click="closeCropper()">Zrušit</button>
                <button class="px-3 py-2 rounded-lg bg-neutral-900 text-white"
                        x-on:click="finishCrop()">Uložit ořez</button>
            </div>
        </div>
    </div>

    {{-- Skripty: Sortable + Cropper + Alpine glue --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet"/>

            <script>
                function galleryPage() {
                    return {
                        previewUrl: null,
                        cropOpen: false,
                        cropper: null,

                        init() {
                            const list = document.getElementById('gallery-list');
                            if (list) {
                                new Sortable(list, {
                                    animation: 150,
                                    onEnd: () => {
                                        const ids = [...list.querySelectorAll('li[data-id]')].map(li => li.dataset.id);
                                        window.Livewire.dispatch('gallery-reorder', ids);
                                    }
                                });
                            }
                            window.addEventListener('open-cropper', (e) => this.openCropper(e.detail.url));
                        },

                        handleFile(e) {
                            const file = e.target.files?.[0];
                            if (!file) return;
                            const r = new FileReader();
                            r.onload = (ev) => { this.previewUrl = ev.target.result; };
                            r.readAsDataURL(file);
                        },

                        openCropper(dataUrl) {
                            this.cropOpen = true;
                            this.$nextTick(() => {
                                this.$refs.cropImage.src = dataUrl;
                                this.cropper?.destroy();
                                this.cropper = new Cropper(this.$refs.cropImage, {
                                    viewMode: 1, dragMode: 'move', aspectRatio: NaN, autoCropArea: 1,
                                });
                            });
                        },

                        closeCropper() {
                            this.cropper?.destroy();
                            this.cropper = null;
                            this.cropOpen = false;
                        },

                        finishCrop() {
                            if (!this.cropper) return;
                            const canvas = this.cropper.getCroppedCanvas({ imageSmoothingEnabled: true });
                            const dataUrl = canvas.toDataURL('image/jpeg', 0.92);

                            // ✅ Livewire v3 – použij $wire
                            this.$wire.set('croppedDataUrl', dataUrl);
                            this.$wire.call('saveCropped');

                            this.closeCropper();
                            this.previewUrl = null;
                        },

                        // tlačítko „Nahrát bez ořezu“
                        emitCropped(dataUrl) {
                            if (!dataUrl) return;
                            window.Livewire.dispatch('upload-cropped', { dataUrl });
                            this.previewUrl = null;
                        }
                    };
                }
            </script>
    @endpush
</div>
