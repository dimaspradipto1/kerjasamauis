<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_jenis_dokumen' => 'required|string|max:255',
            'keterangan'         => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_jenis_dokumen.required' => 'Nama jenis dokumen wajib diisi.',
            'nama_jenis_dokumen.max'      => 'Nama jenis dokumen maksimal 255 karakter.',
        ];
    }
}
