<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KerjasamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nomor_dokumen_kerjasama' => 'required|string|max:255',
            'nomor_dokumen_mitra'     => 'nullable|string|max:255',
            'jenis_dokumen_id'        => 'required|exists:jenis_dokumens,id',
            'mitra_id'                => 'required|exists:mitras,id',
            'unit_kerja_id'           => 'required|exists:unit_kerjas,id',
            'judul_kerjasama'         => 'required|string',
            'deskripsi_kerjasama'     => 'required|string',
            'sumber_dana_id'          => 'required|exists:sumber_danas,id',
            'anggaran'                => 'required|integer|min:0',
            'tanggal_waktu_berlaku'   => 'required|date',
            'tanggal_akhir_berlaku'   => 'required|date|after_or_equal:tanggal_waktu_berlaku',
            'status_kerjasama'        => 'required|string|max:100',
            'hasil_pelaksanaan'       => 'nullable|string',
            'dokumen_file'            => 'nullable|file|mimes:pdf,doc,docx|max:5120', // max 5MB
            
            // Pihak Pihak
            'pihak'                   => 'required|array|size:2',
            'pihak.*.jenis_pihak'     => 'required|string|in:Unit,Mitra',
            'pihak.*.alamat'          => 'nullable|string',
            
            // Penanggung Jawab
            'pihak.*.penanggung_jawab' => 'required|array|min:1',
            'pihak.*.penanggung_jawab.*.nama' => 'required|string|max:255',
            'pihak.*.penanggung_jawab.*.nip' => 'nullable|string|max:100',
            'pihak.*.penanggung_jawab.*.jabatan' => 'nullable|string|max:255',
            'pihak.*.penanggung_jawab.*.nomor_hp' => 'nullable|string|max:50',
            'pihak.*.penanggung_jawab.*.email' => 'nullable|email|max:255',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nomor_dokumen_kerjasama.required' => 'Nomor dokumen kerjasama wajib diisi.',
            'jenis_dokumen_id.required'        => 'Jenis dokumen wajib dipilih.',
            'mitra_id.required'                => 'Mitra wajib dipilih.',
            'unit_kerja_id.required'           => 'Unit kerja wajib dipilih.',
            'judul_kerjasama.required'         => 'Judul kerjasama wajib diisi.',
            'deskripsi_kerjasama.required'     => 'Deskripsi kerjasama wajib diisi.',
            'sumber_dana_id.required'          => 'Sumber dana wajib dipilih.',
            'anggaran.required'                => 'Anggaran wajib diisi.',
            'tanggal_waktu_berlaku.required'   => 'Tanggal awal berlaku wajib diisi.',
            'tanggal_akhir_berlaku.required'   => 'Tanggal akhir berlaku wajib diisi.',
            'tanggal_akhir_berlaku.after_or_equal' => 'Tanggal akhir berlaku harus setelah atau sama dengan tanggal awal.',
            'status_kerjasama.required'        => 'Status kerjasama wajib dipilih.',
            'dokumen_file.mimes'               => 'Format file harus berupa PDF, DOC, atau DOCX.',
            'dokumen_file.max'                 => 'Ukuran file maksimal adalah 5 MB.',
            'pihak.*.jenis_pihak.required'     => 'Jenis pihak wajib ditentukan.',
            'pihak.*.penanggung_jawab.required' => 'Minimal harus ada 1 penanggung jawab per pihak.',
            'pihak.*.penanggung_jawab.*.nama.required' => 'Nama penanggung jawab wajib diisi.',
        ];
    }
}
