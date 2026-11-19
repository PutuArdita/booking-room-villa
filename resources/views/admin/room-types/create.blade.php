@extends('layouts.admin')

@section('title', 'Tambah Tipe Kamar')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Tambah Tipe Kamar</h1>
    <form method="POST" action="{{ route('admin.room-types.store') }}" class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @include('admin.room-types._form')
    </form>
@endsection
