<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed default permissions untuk semua user yang sudah ada di database.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->generateDefaultPermissions();
        }

        $this->command->info('Role permissions berhasil di-seed untuk ' . $users->count() . ' pengguna.');
    }
}
