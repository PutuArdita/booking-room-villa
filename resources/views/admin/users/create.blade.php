@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Tambah Pengguna</h1>
    <form method="POST" action="{{ route('admin.users.store') }}" class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @include('admin.users._form')
    </form>
@endsection