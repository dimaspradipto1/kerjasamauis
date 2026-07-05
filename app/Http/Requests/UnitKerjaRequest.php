<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitKerjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_unit_kerja' => 'required|string|max:255',
            'keterangan'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_unit_kerja.required' => 'Nama unit kerja wajib diisi.',
            'nama_unit_kerja.max'      => 'Nama unit kerja maksimal 255 karakter.',
        ];
    }
}
