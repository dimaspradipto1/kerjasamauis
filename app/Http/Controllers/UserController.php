<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource using Yajra DataTables.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Admin tidak bisa set role — user baru dibuat dengan role 'user'
        if (Auth::user()->roles === 'admin') {
            $data['roles'] = 'user';
        } else {
            $data['roles'] = $data['role'] ?? 'user';
        }
        unset($data['role']);

        $user = User::create($data);

        // Otomatis buat default permissions berdasarkan role
        $user->generateDefaultPermissions();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user    = User::findOrFail($id);
        $oldRole = $user->roles;
        $data    = $request->validated();
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Admin tidak bisa mengubah role pengguna — pertahankan role yang ada
        if (Auth::user()->roles === 'admin') {
            $data['roles'] = $user->roles; // tetap pakai role lama
        } else {
            $data['roles'] = $data['role'] ?? $user->roles;
        }
        unset($data['role']);

        // Hanya update password jika diisi
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Hapus field konfirmasi password
        unset($data['password_confirmation']);

        $user->update($data);

        // Jika role berubah (hanya bisa dilakukan superadmin), regenerasi permissions
        if ($oldRole !== $data['roles']) {
            $user->generateDefaultPermissions();
        }

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deletion
        if (\Illuminate\Support\Facades\Auth::id() == $user->id) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Show the form for updating the specified user's password.
     */
    public function updatePasswordForm($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.update-password', compact('user'));
    }

    /**
     * Update the specified user's password in storage.
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'Password pengguna berhasil diperbarui.');
    }
}
