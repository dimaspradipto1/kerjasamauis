<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Define the middleware for the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = \Illuminate\Support\Facades\Auth::user();
                if ($user && ($user->role == 'user' || $user->role == 'pimpinan')) {
                    abort(403, 'Anda tidak memiliki hak akses untuk mengelola data pengguna.');
                }
                return $next($request);
            }),
        ];
    }

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
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Map role request parameter to roles database column
        $data['roles'] = $data['role'];
        unset($data['role']);

        User::create($data);

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
        $user = User::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Map role request parameter to roles database column
        $data['roles'] = $data['role'];
        unset($data['role']);

        // Hanya update password jika diisi. Jika kosong, hapus dari data agar password lama tetap digunakan.
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Hapus field konfirmasi password agar tidak masuk ke database
        unset($data['password_confirmation']);

        $user->update($data);

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
