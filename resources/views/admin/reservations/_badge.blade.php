@php
    $class = match ($booking->status) {
        'pending_payment' => 'bg-yellow-100 text-yellow-800',
        'confirmed' => 'bg-green-100 text-green-800',
        'checked_in' => 'bg-blue-100 text-blue-800',
        'rejected', 'cancelled' => 'bg-red-100 text-red-800',
        default => 'bg-gray-200 text-gray-700',
    };
@endphp
<span class="rounded px-2 py-1 text-xs {{ $class }}">{{ $booking->status_label }}</span>