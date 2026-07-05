<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId   = $this->route('user');
        $authUser = Auth::user();
        $allowedRoles = ['admin', 'pimpinan', 'user'];
        if ($authUser && $authUser->roles === 'superadmin') {
            $allowedRoles[] = 'superadmin';
        }

        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users,email,' . $userId,
            'role'                  => 'required|string|in:' . implode(',', $allowedRoles),
            'is_active'             => 'nullable|boolean',
            // Password opsional saat update
            'password'              => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan.',
            'role.required'      => 'Hak akses wajib dipilih.',
            'role.in'            => 'Hak akses tidak valid.',
            'password.min'       => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];
    }
}
