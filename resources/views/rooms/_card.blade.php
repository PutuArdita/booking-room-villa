<a href="{{ route('rooms.show', $type->slug) }}"
    class="group block overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sand-100 transition hover:shadow-lg">
    <div class="aspect-[4/3] overflow-hidden bg-sand-100">
        @if ($type->images->first())
            <img src="{{ $type->images->first()->url }}" alt="{{ $type->name }}"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-sm text-gray-400">
                {{ __('Belum ada foto') }}
            </div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-display text-lg font-semibold text-gray-900">{{ $type->name }}</h3>
        <p class="mt-1 flex items-center gap-1 text-sm text-gray-600">
            <x-heroicon-o-users class="h-4 w-4" /> {{ $type->capacity }} {{ __('tamu') }}
        </p>
        <p class="mt-3 font-semibold text-brand-700">
            Rp {{ number_format($type->base_price, 0, ',', '.') }}
            <span class="text-sm font-normal text-gray-500">/ {{ __('malam') }}</span>
        </p>
    </div>
</a>