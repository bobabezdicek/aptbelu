@extends('layouts.admin.app')
@section('title','Ceník')
@section('content')
{{--    <div class="bg-white rounded-xl p-6 shadow ring-1 ring-black/5">--}}
{{--        <h2 class="text-lg font-semibold">Ceník</h2>--}}
{{--        <p class="text-sm text-neutral-600 mt-2">Zatím prázdná stránka.</p>--}}
{{--    </div>--}}

    @livewire('admin.pricing-manager')
@endsection
