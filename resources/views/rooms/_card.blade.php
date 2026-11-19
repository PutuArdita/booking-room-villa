<a href="{{ route('rooms.show', $type->slug) }}" class="block overflow-hidden rounded border hover:shadow">
    @if ($type->images->first())
        <img src="{{ $type->images->first()->url }}" alt="{{ $type->name }}" class="h-48 w-full object-cover">
    @else
        <div class="flex h-48 items-center justify-center bg-gray-100 text-sm text-gray-400">
            {{ __('Belum ada foto') }}
        </div>
    @endif
    <div class="p-4">
        <h3 class="font-bold">{{ $type->name }}</h3>
        <p class="text-sm text-gray-600">{{ $type->capacity }} {{ __('tamu') }}</p>
        <p class="mt-2 font-semibold">
            Rp {{ number_format($type->base_price, 0, ',', '.') }}
            <span class="text-sm font-normal text-gray-500">/ {{ __('malam') }}</span>
        </p>
    </div>
</a>
