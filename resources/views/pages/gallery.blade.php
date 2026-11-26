@extends('layouts.public')

@section('title', __('Galeri') . ' - ' . config('villa.name'))

@section('content')
    @include('layouts._page-header', [
        'title' => __('Galeri'),
        'subtitle' => __('Foto-foto villa akan segera ditampilkan.'),
    ])

    <div class="mx-auto max-w-7xl px-4 py-16 text-center text-gray-500 lg:px-6">
        {{ __('Galeri akan segera tersedia.') }}
    </div>
@endsection