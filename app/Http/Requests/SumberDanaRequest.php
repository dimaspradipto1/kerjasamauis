<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SumberDanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sumber_dana' => 'required|string|max:255',
            'keterangan'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sumber_dana.required' => 'Nama sumber dana wajib diisi.',
            'nama_sumber_dana.max'      => 'Nama sumber dana maksimal 255 karakter.',
        ];
    }
}
