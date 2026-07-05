<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    /**
     * Tampilkan halaman manajemen izin untuk pengguna tertentu.
     * Hanya superadmin yang dapat mengakses.
     */
    public function index(User $user)
    {
        // Hanya superadmin yang dapat mengatur izin
        if (Auth::user()->roles !== 'superadmin') {
            abort(403, 'Hanya Super Admin yang dapat mengatur hak akses pengguna.');
        }

        // Tidak bisa mengatur izin diri sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat mengatur hak akses akun Anda sendiri.');
        }

        // Pastikan user memiliki permission records, buat jika belum ada
        $existingCount = $user->rolePermissions()->count();
        if ($existingCount < count(RolePermission::MODULES)) {
            $user->generateDefaultPermissions();
        }

        $matrix = $user->getPermissionsMatrix();
        $modules = RolePermission::MODULES;

        return view('pages.role-permission.index', compact('user', 'matrix', 'modules'));
    }

    /**
     * Simpan perubahan izin untuk pengguna tertentu.
     */
    public function update(Request $request, User $user)
    {
        // Hanya superadmin yang dapat mengatur izin
        if (Auth::user()->roles !== 'superadmin') {
            abort(403, 'Hanya Super Admin yang dapat mengatur hak akses pengguna.');
        }

        // Tidak bisa mengatur izin diri sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat mengatur hak akses akun Anda sendiri.');
        }

        $permissions = $request->input('permissions', []);

        foreach (RolePermission::MODULES as $moduleKey => $_) {
            $moduleData = $permissions[$moduleKey] ?? [];

            RolePermission::updateOrCreate(
                ['user_id' => $user->id, 'module' => $moduleKey],
                [
                    'can_create' => isset($moduleData['can_create']),
                    'can_read'   => isset($moduleData['can_read']),
                    'can_update' => isset($moduleData['can_update']),
                    'can_delete' => isset($moduleData['can_delete']),
                ]
            );
        }

        return redirect()->route('role-permission.index', $user->id)
            ->with('success', 'Hak akses pengguna "' . $user->name . '" berhasil diperbarui.');
    }
}
