@extends('layouts.admin')

@section('title', 'Tambah Kamar')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Tambah Kamar</h1>
    <form method="POST" action="{{ route('admin.rooms.store') }}" class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @include('admin.rooms._form')
    </form>
@endsection
