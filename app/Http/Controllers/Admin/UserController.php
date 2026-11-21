<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('role')->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'owner'])],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create($data + ['is_active' => true]);

        return redirect()->route('admin.users.index')->with('success', 'Akun ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $isSelf = $user->is($request->user());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'owner'])],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($isSelf) {
            $data['role'] = $user->role; // peran sendiri tidak boleh diubah
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $passwordChanged = isset($data['password']);

        $user->update($data);

        if ($passwordChanged && !$isSelf) {
            $this->endSessions($user);
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun diperbarui.');
    }

    public function toggleActive(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        if (!$user->is_active) {
            $this->endSessions($user);
        }

        return back()->with('success', $user->is_active ? 'Akun diaktifkan.' : 'Akun dinonaktifkan.');
    }

    private function endSessions(User $user): void
    {
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }
    }
}
