@extends('layouts.admin')

@section('title', 'Blokir Tanggal')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Blokir Tanggal</h1>
        <a href="{{ route('admin.blocked-dates.create') }}"
            class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Tambah</a>
    </div>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Kamar</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Alasan</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($blocks as $block)
                    <tr class="border-t {{ $block->end_date->isPast() ? 'text-gray-400' : '' }}">
                        <td class="p-3">{{ $block->room->roomType->name }} - {{ $block->room->name }}</td>
                        <td class="p-3">{{ $block->start_date->format('d/m/Y') }} &rarr; {{ $block->end_date->format('d/m/Y') }}
                        </td>
                        <td class="p-3">{{ $block->reason ?: '-' }}</td>
                        <td class="p-3">
                            <form method="POST" action="{{ route('admin.blocked-dates.destroy', $block) }}"
                                onsubmit="return confirm('Hapus blokir ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">Belum ada blokir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $blocks->links() }}</div>
@endsection