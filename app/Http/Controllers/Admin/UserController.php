<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'admin', 403, 'Akun Admin tidak dapat dihapus dari sini.');
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
