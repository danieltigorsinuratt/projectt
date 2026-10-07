<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'asc')->get();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:6',
            'nip_nik' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'nip_nik' => $request->nip_nik,
            'status' => $request->status,
        ]);

        ActivityLog::log('Tambah User', "Menambahkan user baru: {$user->nama_lengkap} ({$user->username})");

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6',
            'nip_nik' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $updateData = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'nip_nik' => $request->nip_nik,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        ActivityLog::log('Edit User', "Memperbarui data pengguna: {$user->nama_lengkap}");

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->nama_lengkap;
        $user->delete();

        ActivityLog::log('Hapus User', "Menghapus akun pengguna: {$name}");

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
