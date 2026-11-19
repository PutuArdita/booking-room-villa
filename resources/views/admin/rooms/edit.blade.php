@extends('layouts.admin')

@section('title', 'Ubah Kamar')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Ubah Kamar</h1>
    <form method="POST" action="{{ route('admin.rooms.update', $room) }}"
          class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @method('PUT')
        @include('admin.rooms._form')
    </form>
@endsection
