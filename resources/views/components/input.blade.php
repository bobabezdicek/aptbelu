@props([
    'label' => null,
    'type' => 'text',
    'placeholder' => null,
])

<div class="space-y-1">
    @if($label)
        <label class="text-xs font-medium text-neutral-700">{{ $label }}</label>
    @endif

    <input type="{{ $type }}"
           placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
             'class' => 'w-full rounded-lg border border-neutral-300/70 bg-white
                        px-3 py-2 text-sm text-neutral-900
                        placeholder:text-neutral-400
                        focus:outline-none focus:ring-2 focus:ring-neutral-900/5 focus:border-neutral-400
                        transition'
        ]) }}>

    @php($model = $attributes->whereStartsWith('wire:model')->first())
    @error($model)
    <div class="text-xs text-red-600">{{ $message }}</div>
    @enderror
</div>
