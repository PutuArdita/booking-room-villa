{{-- @foreach ([
['home', 'home', __('Beranda')],
['rooms.index', 'rooms.*', __('Kamar')],
['availability.index', 'availability.*', __('Cek Ketersediaan')],
] as [$route, $pattern, $label])
<a href="{{ route($route) }}" @class([ 'hover:text-brand-700' , 'font-semibold text-brand-700'=>
    request()->routeIs($pattern),
    'text-gray-700' => !request()->routeIs($pattern),
    ])>{{ $label }}</a>
@endforeach

<span class="flex items-center gap-1 text-xs">
    @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $name)
    <a href="{{ route('locale', $code) }}" @class([ 'rounded px-2 py-1' , 'bg-brand-600 text-white'=> app()->getLocale()
        === $code,
        'text-gray-600 hover:bg-gray-100' => app()->getLocale() !== $code,
        ])>{{ $name }}</a>
    @endforeach
</span> --}}

@php
    $links = [
        [route('home'), __('Beranda'), request()->routeIs('home')],
        [route('home') . '#tentang', __('Tentang'), false],
        [route('rooms.index'), __('Kamar'), request()->routeIs('rooms.*')],
        [route('home') . '#kontak', __('Kontak'), false],
    ];
@endphp

@foreach ($links as [$url, $label, $active])
    <a href="{{ $url }}" @class([
        'border-b-2 py-1 transition hover:opacity-100',
        'border-current font-semibold' => $active,
        'border-transparent opacity-90' => !$active,
    ])>{{ $label }}</a>
@endforeach

<span class="flex items-center gap-1 text-xs">
    @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $name)
        <a href="{{ route('locale', $code) }}" @class([
            'rounded px-2 py-1 font-semibold transition',
            'bg-brand-600 text-white' => app()->getLocale() === $code,
            'hover:bg-black/10' => app()->getLocale() !== $code,
        ])>{{ $name }}</a>
    @endforeach
</span>