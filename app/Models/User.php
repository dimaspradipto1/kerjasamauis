<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role attribute (compatibility with both roles and role).
     */
    public function getRoleAttribute()
    {
        return $this->roles;
    }

    /**
     * Set the role attribute.
     */
    public function setRoleAttribute($value)
    {
        $this->attributes['roles'] = $value;
    }

    /**
     * Relasi ke RolePermission.
     */
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    /**
     * Cek apakah user memiliki izin tertentu pada modul tertentu.
     * $action: 'can_create' | 'can_read' | 'can_update' | 'can_delete'
     */
    public function hasPermission(string $module, string $action): bool
    {
        // Superadmin selalu punya akses penuh
        if ($this->roles === 'superadmin') {
            return true;
        }

        $permission = $this->rolePermissions()
            ->where('module', $module)
            ->first();

        if (!$permission) {
            // Fallback: tidak ada record, default false (kecuali can_read)
            return $action === 'can_read';
        }

        return (bool) $permission->{$action};
    }

    /**
     * Return matriks izin semua modul sebagai array.
     * ['modul' => ['can_create' => bool, ...], ...]
     */
    public function getPermissionsMatrix(): array
    {
        $permissions = $this->rolePermissions->keyBy('module');
        $matrix = [];

        foreach (RolePermission::MODULES as $moduleKey => $moduleLabel) {
            $perm = $permissions->get($moduleKey);
            $matrix[$moduleKey] = [
                'label'      => $moduleLabel,
                'can_create' => $perm ? $perm->can_create : false,
                'can_read'   => $perm ? $perm->can_read   : false,
                'can_update' => $perm ? $perm->can_update : false,
                'can_delete' => $perm ? $perm->can_delete : false,
            ];
        }

        return $matrix;
    }

    /**
     * Generate default permissions berdasarkan role dan simpan ke database.
     * Dipanggil saat user baru dibuat.
     */
    public function generateDefaultPermissions(): void
    {
        $role = $this->roles;
        $defaults = RolePermission::ROLE_DEFAULTS[$role] ?? RolePermission::ROLE_DEFAULTS['user'];
        $overrides = RolePermission::MODULE_OVERRIDES[$role] ?? [];

        foreach (RolePermission::MODULES as $moduleKey => $_) {
            // Cek apakah ada override khusus untuk modul ini
            if (isset($overrides[$moduleKey])) {
                $moduleDefaults = $overrides[$moduleKey];
            }
            // Cek apakah modul hanya untuk superadmin
            elseif (in_array($moduleKey, RolePermission::SUPERADMIN_ONLY_MODULES) && $role !== 'superadmin') {
                $moduleDefaults = [
                    'can_create' => false,
                    'can_read'   => false,
                    'can_update' => false,
                    'can_delete' => false,
                ];
            } else {
                $moduleDefaults = $defaults;
            }

            RolePermission::updateOrCreate(
                ['user_id' => $this->id, 'module' => $moduleKey],
                $moduleDefaults
            );
        }
    }
}

