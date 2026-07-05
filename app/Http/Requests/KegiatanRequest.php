<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kerjasama_id'            => 'required|exists:kerjasamas,id',
            'unit_kerja_id'           => 'required|exists:unit_kerjas,id',
            'mitra_id'                => 'required|exists:mitras,id',
            'sasaran_kinerja_id'      => 'required|exists:sasaran_kinerjas,id',
            'bentuk_kegiatan_id'      => 'required|exists:bentuk_kegiatans,id',
            'indikator_id'            => 'required|exists:indikator_sasarans,id',
            'nomor_dokumen_kegiatan'  => 'nullable|string|max:255',
            'nomor_dokumen_mitra'     => 'nullable|string|max:255',
            'judul_kegiatan'          => 'required|string',
            'tanggal_awal_kegiatan'   => 'required|date',
            'tanggal_akhir_kegiatan'  => 'required|date|after_or_equal:tanggal_awal_kegiatan',
            'ruang_lingkup'           => 'nullable|string',
            'hasil_pelakasanaan'      => 'nullable|string',
            'nilai_kontrak'           => 'nullable|integer|min:0',
            'link_dokumen_kegiatan'   => 'nullable|string|max:255',
            'dokumen_file'            => 'nullable|file|mimes:pdf,doc,docx|max:5120', // max 5MB

            // Pihak Pihak
            'pihak'                                => 'required|array|size:2',
            'pihak.*.jenis_pihak'                  => 'required|string|in:Unit,Mitra',
            'pihak.*.penanggung_jawab'             => 'required|string|max:255', // Pihak Penanggung Jawab name string
            
            // Penanggung Jawab PJs
            'pihak.*.penanggung_jawab_pjs'         => 'required|array|min:1',
            'pihak.*.penanggung_jawab_pjs.*.nama'  => 'required|string|max:255',
            'pihak.*.penanggung_jawab_pjs.*.nip'   => 'nullable|string|max:100',
            'pihak.*.penanggung_jawab_pjs.*.jabatan' => 'nullable|string|max:255',
            'pihak.*.penanggung_jawab_pjs.*.nomor_hp' => 'nullable|string|max:50',
            'pihak.*.penanggung_jawab_pjs.*.email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'kerjasama_id.required'           => 'Induk kerjasama wajib dipilih.',
            'unit_kerja_id.required'          => 'Unit kerja pengusul wajib dipilih.',
            'mitra_id.required'               => 'Mitra wajib dipilih.',
            'bentuk_kegiatan_id.required'     => 'Bentuk kegiatan wajib dipilih.',
            'sasaran_kinerja_id.required'     => 'Sasaran kinerja wajib dipilih.',
            'indikator_id.required'           => 'Indikator sasaran wajib dipilih.',
            'judul_kegiatan.required'         => 'Judul kegiatan wajib diisi.',
            'tanggal_awal_kegiatan.required'  => 'Tanggal awal kegiatan wajib diisi.',
            'tanggal_akhir_kegiatan.required' => 'Tanggal akhir kegiatan wajib diisi.',
            'tanggal_akhir_kegiatan.after_or_equal' => 'Tanggal akhir kegiatan harus setelah atau sama dengan tanggal awal.',
            'dokumen_file.mimes'              => 'Format file harus berupa PDF, DOC, atau DOCX.',
            'dokumen_file.max'                => 'Ukuran file maksimal adalah 5 MB.',
            'pihak.*.jenis_pihak.required'    => 'Jenis pihak wajib ditentukan.',
            'pihak.*.penanggung_jawab.required' => 'Nama Pihak Penanggung Jawab wajib diisi.',
            'pihak.*.penanggung_jawab_pjs.required' => 'Minimal harus ada 1 penanggung jawab per pihak.',
            'pihak.*.penanggung_jawab_pjs.*.nama.required' => 'Nama penanggung jawab wajib diisi.',
        ];
    }
}
