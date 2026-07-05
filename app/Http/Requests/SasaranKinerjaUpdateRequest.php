<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SasaranKinerjaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sasaran_kinerja'               => 'required|string|max:500',
            'keterangan'                    => 'nullable|string',
            'level'                         => 'required|string|max:255',
            // indikator (array) - nullable so edit can submit without adding new indicators
            'indikator'                     => 'nullable|array',
            'indikator.*.id'                => 'nullable|integer|exists:indikator_sasarans,id',
            'indikator.*.indikator_sasaran' => 'required_with:indikator|string|max:500',
            'indikator.*.keterangan'        => 'nullable|string',
            'indikator.*.volume'            => 'nullable|integer',
            'indikator.*.satuan'            => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'sasaran_kinerja.required'                => 'Sasaran kinerja wajib diisi.',
            'level.required'                          => 'Level wajib diisi.',
            'indikator.*.indikator_sasaran.required_with' => 'Nama indikator wajib diisi.',
        ];
    }
}
