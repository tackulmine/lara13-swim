@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Beranda</h1>

  <p class="mb-4">Selamat datang, {{ auth()->user()->name }}</p>
@endsection
