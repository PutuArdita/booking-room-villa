@php($isSelf = isset($user) && $user->is(auth()->user()))

@if ($errors->any())
    <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
            class="w-full rounded border p-2">
    </div>

    <div>
        <label class="block text-sm">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
            class="w-full rounded border p-2">
    </div>

    <div>
        <label class="block text-sm">Peran</label>
        @if ($isSelf)
            <input type="hidden" name="role" value="{{ $user->role }}">
        @endif
        <select name="role" class="w-full rounded border p-2" @disabled($isSelf)>
            @foreach (['admin' => 'Admin', 'owner' => 'Owner'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? 'admin') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if ($isSelf)
            <p class="mt-1 text-xs text-gray-500">Peran akun sendiri tidak dapat diubah.</p>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label
                class="block text-sm">{{ isset($user) ? 'Password baru (kosongkan jika tidak diganti)' : 'Password' }}</label>
            <input type="password" name="password" @required(!isset($user)) class="w-full rounded border p-2">
        </div>
        <div>
            <label class="block text-sm">Ulangi password</label>
            <input type="password" name="password_confirmation" @required(!isset($user))
                class="w-full rounded border p-2">
        </div>
    </div>
    <p class="text-xs text-gray-500">Minimal 8 karakter.</p>

    <div class="flex gap-3">
        <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Batal</a>
    </div>
</div>