@extends("layouts.app")
@section("title","Stránka nenalezena")
@section("content")
  <div class="container mx-auto px-4 py-24 text-center">
    <h1 class="text-4xl font-bold mb-4">404</h1>
    <p class="mb-8">Tahle stránka neexistuje.</p>
    <a href="{{ route("home") }}" class="inline-block px-4 py-2 border rounded">Zpět domů</a>
  </div>
@endsection
