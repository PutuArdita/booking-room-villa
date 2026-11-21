<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit()
    {
        return view('admin.account.edit');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:100'],
        ]);

        $request->user()->update($data);

        return back()->with('success', 'Profil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8), 'different:current_password'],
        ]);

        $request->user()->update(['password' => $data['password']]);

        return back()->with('success', 'Password diperbarui.');
    }
}
