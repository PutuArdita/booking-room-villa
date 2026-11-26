@php
    $links = [
        [route('home'), __('Beranda'), request()->routeIs('home')],
        [route('rooms.index'), __('Kamar'), request()->routeIs('rooms.*')],
        [route('facilities.index'), __('Fasilitas'), request()->routeIs('facilities.*')],
        [route('gallery.index'), __('Galeri'), request()->routeIs('gallery.*')],
        [route('contact.index'), __('Kontak'), request()->routeIs('contact.*')],
    ];
@endphp

@foreach ($links as [$url, $label, $active])
    <a href="{{ $url }}" @class(['nav-link', 'is-active' => $active])>{{ $label }}</a>
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