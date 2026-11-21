@extends('layouts.admin')

@section('title', 'Ubah Pengguna')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Ubah Pengguna</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @method('PUT')
        @include('admin.users._form')
    </form>
@endsection