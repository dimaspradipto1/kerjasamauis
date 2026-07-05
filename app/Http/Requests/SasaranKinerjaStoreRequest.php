<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SasaranKinerjaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sasaran_kinerja'             => 'required|string|max:500',
            'keterangan'                  => 'nullable|string',
            'level'                       => 'required|string|max:255',
            // indikator (array)
            'indikator'                   => 'required|array|min:1',
            'indikator.*.indikator_sasaran' => 'required|string|max:500',
            'indikator.*.keterangan'      => 'nullable|string',
            'indikator.*.volume'          => 'nullable|integer',
            'indikator.*.satuan'          => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'sasaran_kinerja.required'                => 'Sasaran kinerja wajib diisi.',
            'level.required'                          => 'Level wajib diisi.',
            'indikator.required'                      => 'Minimal 1 indikator harus diisi.',
            'indikator.min'                           => 'Minimal 1 indikator harus diisi.',
            'indikator.*.indikator_sasaran.required'  => 'Nama indikator wajib diisi.',
        ];
    }
}
