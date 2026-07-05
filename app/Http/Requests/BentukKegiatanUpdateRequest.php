<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BentukKegiatanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_kegiatan'       => 'required|string|in:Pendidikan,Penelitian,Pengabdian',
            'nama_bentuk_kegiatan' => 'required|string|max:500',
            'keterangan'           => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_kegiatan.required'       => 'Jenis kegiatan wajib dipilih.',
            'jenis_kegiatan.in'             => 'Jenis kegiatan tidak valid.',
            'nama_bentuk_kegiatan.required' => 'Nama bentuk kegiatan wajib diisi.',
            'nama_bentuk_kegiatan.max'      => 'Nama bentuk kegiatan maksimal 500 karakter.',
        ];
    }
}
