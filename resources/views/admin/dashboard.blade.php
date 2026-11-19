@extends('layouts.admin')

@section('title', 'Dasbor')

@section('content')
    <h1 class="text-2xl font-bold">Dasbor</h1>
    <p class="mt-2">Selamat datang, {{ auth()->user()->name }}.</p>
@endsection
