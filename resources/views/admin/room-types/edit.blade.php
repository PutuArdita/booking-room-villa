@extends('layouts.admin')

@section('title', 'Ubah Tipe Kamar')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Ubah Tipe Kamar</h1>
    <form method="POST" action="{{ route('admin.room-types.update', $roomType) }}"
        class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @method('PUT')
        @include('admin.room-types._form')
    </form>
@endsection
