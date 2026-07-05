<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KriteriaMitraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kriteria_mitra' => 'required|string|max:500',
            'keterangan'     => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'kriteria_mitra.required' => 'Kriteria mitra wajib diisi.',
            'kriteria_mitra.max'      => 'Kriteria mitra maksimal 500 karakter.',
        ];
    }
}
