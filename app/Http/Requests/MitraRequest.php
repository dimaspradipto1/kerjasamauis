<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MitraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_mitra'            => 'required|string|in:Perguruan Tinggi,Non Perguruan Tinggi',
            'nama_mitra'             => 'required|string|max:500',
            'kriteria_mitra_id'      => 'required|exists:kriteria_mitras,id',
            'nomor_izin_usaha'       => 'nullable|string|max:255',
            'npwp'                   => 'nullable|string|max:255',
            'lingkup_mitra'          => 'required|string|in:Lokal,Regional,Nasional,Internasional',
            'provinsi'               => 'required|string|max:255',
            'kabupaten_kota'         => 'required|string|max:255',
            'kecamatan'              => 'required|string|max:255',
            'kodepos'                => 'nullable|string|max:20',
            'alamat'                 => 'nullable|string',
            'email'                  => 'nullable|email|max:255',
            'no_telp'                => 'nullable|string|max:50',
            'website'                => 'nullable|string|max:255',
            
            // Kontak Mitra
            'kontak'                 => 'required|array|min:1',
            'kontak.*.id'            => 'nullable|integer|exists:kontak_mitras,id',
            'kontak.*.nama_kontak'   => 'required|string|max:255',
            'kontak.*.jabatan'       => 'required|string|max:255',
            'kontak.*.nomor_handphone' => 'required|string|max:50',
            'kontak.*.email'         => 'required|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_mitra.required'       => 'Jenis mitra wajib dipilih.',
            'nama_mitra.required'        => 'Nama mitra wajib diisi.',
            'kriteria_mitra_id.required' => 'Kriteria mitra wajib dipilih.',
            'lingkup_mitra.required'     => 'Lingkup mitra wajib dipilih.',
            'provinsi.required'          => 'Provinsi wajib dipilih.',
            'kabupaten_kota.required'    => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan.required'         => 'Kecamatan wajib dipilih.',
            'kontak.required'            => 'Minimal 1 kontak mitra wajib ditambahkan.',
            'kontak.*.nama_kontak.required'    => 'Nama kontak wajib diisi.',
            'kontak.*.jabatan.required'        => 'Jabatan kontak wajib diisi.',
            'kontak.*.nomor_handphone.required' => 'Nomor handphone kontak wajib diisi.',
            'kontak.*.email.required'          => 'Email kontak wajib diisi.',
            'kontak.*.email.email'             => 'Format email kontak tidak valid.',
        ];
    }
}
