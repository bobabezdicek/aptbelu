@extends('layouts.app')
@section('title','Aptbelu')
@section('content')
    @include('partials.hero')

    @include('partials.about-us')

    @livewire('gallery')

    @include('partials.price-list')

    @livewire('calendar')

    @include('partials.location-map')

    @include('partials.contact')

    @include('partials.footer')
@endsection
