<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_staff'    => 'required|string|max:255',
            'nup'           => 'nullable|string|max:100',
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'jabatan'       => 'nullable|string|max:255',
            'email'         => 'nullable|email|max:255',
            'nomor_hp'      => 'nullable|string|max:50',
            'alamat'        => 'nullable|string',
            'status'        => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_staff.required'  => 'Nama staff wajib diisi.',
            'unit_kerja_id.exists' => 'Unit kerja yang dipilih tidak valid.',
            'email.email'          => 'Format email tidak valid.',
            'status.required'      => 'Status wajib dipilih.',
        ];
    }
}
