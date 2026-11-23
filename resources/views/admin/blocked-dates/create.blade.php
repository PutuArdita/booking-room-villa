@extends('layouts.admin')

@section('title', 'Tambah Blokir')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Tambah Blokir Tanggal</h1>

    <form method="POST" action="{{ route('admin.blocked-dates.store') }}"
        class="max-w-xl space-y-4 rounded bg-white p-6 shadow">
        @csrf

        @if ($errors->any())
            <ul class="list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div>
            <label class="block text-sm">Kamar</label>
            <select name="room_id" required class="input">
                <option value="">Pilih kamar</option>
                @foreach ($rooms->groupBy('roomType.name') as $typeName => $group)
                    <optgroup label="{{ $typeName }}">
                        @foreach ($group as $room)
                            <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm">Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" min="{{ today()->toDateString() }}"
                    required class="input">
            </div>
            <div>
                <label class="block text-sm">Selesai (malam terakhir diblokir)</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" min="{{ today()->toDateString() }}"
                    required class="input">
            </div>
        </div>
        <p class="text-xs text-gray-500">
            Tanggal selesai ikut diblokir. Tamu baru bisa check-in lagi sehari setelahnya.
        </p>

        <div>
            <label class="block text-sm">Alasan (opsional)</label>
            <input type="text" name="reason" value="{{ old('reason') }}" maxlength="255" class="input">
        </div>

        <div class="flex gap-3">
            <button class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Simpan</button>
            <a href="{{ route('admin.blocked-dates.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
@endsection