@props(['type' => 'success'])

@php
    $styles = match ($type) {
        'error' => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-yellow-200 bg-yellow-50 text-yellow-800',
        default => 'border-green-200 bg-green-50 text-green-800',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-lg border p-3 text-sm ' . $styles]) }}
    role="alert">
    @if ($type === 'error')
        <x-heroicon-o-x-circle class="h-5 w-5 shrink-0" />
    @elseif ($type === 'warning')
        <x-heroicon-o-exclamation-triangle class="h-5 w-5 shrink-0" />
    @else
        <x-heroicon-o-check-circle class="h-5 w-5 shrink-0" />
    @endif
    <div>{{ $slot }}</div>
</div>