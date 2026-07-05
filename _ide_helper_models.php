<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $jenis_kegiatan
 * @property string $nama_bentuk_kegiatan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan whereJenisKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan whereNamaBentukKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukKegiatan whereUpdatedAt($value)
 */
	class BentukKegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sasaran_kinerja_id
 * @property string $indikator_sasaran
 * @property string|null $keterangan
 * @property int|null $volume
 * @property string|null $satuan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SasaranKinerja $sasaranKinerja
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereIndikatorSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereSasaranKinerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndikatorSasaran whereVolume($value)
 */
	class IndikatorSasaran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_jenis_dokumen
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereNamaJenisDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisDokumen whereUpdatedAt($value)
 */
	class JenisDokumen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $kerjasama_id
 * @property int $unit_kerja_id
 * @property int $mitra_id
 * @property int $sasaran_kinerja_id
 * @property int $bentuk_kegiatan_id
 * @property int $indikator_id
 * @property string|null $nomor_dokumen_kegiatan
 * @property string|null $nomor_dokumen_mitra
 * @property string $judul_kegiatan
 * @property \Illuminate\Support\Carbon $tanggal_awal_kegiatan
 * @property \Illuminate\Support\Carbon $tanggal_akhir_kegiatan
 * @property string|null $ruang_lingkup
 * @property string|null $hasil_pelakasanaan
 * @property int|null $nilai_kontrak
 * @property string|null $link_dokumen_kegiatan
 * @property string|null $url_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BentukKegiatan $bentukKegiatan
 * @property-read \App\Models\IndikatorSasaran $indikator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KegiatanPihak> $kegiatanPihaks
 * @property-read int|null $kegiatan_pihaks_count
 * @property-read \App\Models\Kerjasama $kerjasama
 * @property-read \App\Models\Mitra $mitra
 * @property-read \App\Models\SasaranKinerja $sasaranKinerja
 * @property-read \App\Models\UnitKerja $unitKerja
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereBentukKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereHasilPelakasanaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereIndikatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereJudulKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereKerjasamaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereLinkDokumenKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereMitraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereNilaiKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereNomorDokumenKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereNomorDokumenMitra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereRuangLingkup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereSasaranKinerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereTanggalAkhirKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereTanggalAwalKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereUnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kegiatan whereUrlFile($value)
 */
	class Kegiatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $kegiatan_id
 * @property string $pihak_ke
 * @property string $jenis_pihak
 * @property string|null $nomor_surat_izin
 * @property string|null $penanggung_jawab
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kegiatan $kegiatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KegiatanPj> $penanggungJawabs
 * @property-read int|null $penanggung_jawabs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak whereJenisPihak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak whereNomorSuratIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak wherePenanggungJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak wherePihakKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPihak whereUpdatedAt($value)
 */
	class KegiatanPihak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $kegiatan_pihak_id
 * @property string $nama
 * @property string|null $nip
 * @property string|null $jabatan
 * @property string|null $nomor_hp
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KegiatanPihak $kegiatanPihak
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereKegiatanPihakId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereNomorHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KegiatanPj whereUpdatedAt($value)
 */
	class KegiatanPj extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nomor_dokumen_kerjasama
 * @property string|null $nomor_dokumen_mitra
 * @property int $jenis_dokumen_id
 * @property int $mitra_id
 * @property int $unit_kerja_id
 * @property string $judul_kerjasama
 * @property string $deskripsi_kerjasama
 * @property int $sumber_dana_id
 * @property int $anggaran
 * @property \Illuminate\Support\Carbon $tanggal_waktu_berlaku
 * @property \Illuminate\Support\Carbon $tanggal_akhir_berlaku
 * @property string $status_kerjasama
 * @property string|null $url_file
 * @property string|null $hasil_pelaksanaan
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JenisDokumen $jenisDokumen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KerjasamaPihak> $kerjasamaPihaks
 * @property-read int|null $kerjasama_pihaks_count
 * @property-read \App\Models\Mitra $mitra
 * @property-read \App\Models\SumberDana $sumberDana
 * @property-read \App\Models\UnitKerja $unitKerja
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereDeskripsiKerjasama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereHasilPelaksanaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereJenisDokumenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereJudulKerjasama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereMitraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereNomorDokumenKerjasama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereNomorDokumenMitra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereStatusKerjasama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereSumberDanaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereTanggalAkhirBerlaku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereTanggalWaktuBerlaku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereUnitKerjaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kerjasama whereUrlFile($value)
 */
	class Kerjasama extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $kerjasama_pihak_id
 * @property string $nama
 * @property string $nip
 * @property string $jabatan
 * @property string $nomor_hp
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KerjasamaPihak $kerjasamaPihak
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereKerjasamaPihakId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereNomorHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPenanggungJawab whereUpdatedAt($value)
 */
	class KerjasamaPenanggungJawab extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $kerjasama_id
 * @property string $pihak_ke
 * @property string $jenis_pihak
 * @property string|null $alamat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kerjasama $kerjasama
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KerjasamaPenanggungJawab> $penanggungJawabs
 * @property-read int|null $penanggung_jawabs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak whereJenisPihak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak whereKerjasamaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak wherePihakKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KerjasamaPihak whereUpdatedAt($value)
 */
	class KerjasamaPihak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mitra_id
 * @property string $nama_kontak
 * @property string $jabatan
 * @property string $nomor_handphone
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mitra $mitra
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereMitraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereNamaKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereNomorHandphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KontakMitra whereUpdatedAt($value)
 */
	class KontakMitra extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kriteria_mitra
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra whereKriteriaMitra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KriteriaMitra whereUpdatedAt($value)
 */
	class KriteriaMitra extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $jenis_mitra
 * @property string $nama_mitra
 * @property int $kriteria_mitra_id
 * @property string|null $nomor_izin_usaha
 * @property string|null $npwp
 * @property string $lingkup_mitra
 * @property string $provinsi
 * @property string $kabupaten_kota
 * @property string $kecamatan
 * @property string|null $kodepos
 * @property string|null $alamat
 * @property string|null $email
 * @property string|null $no_telp
 * @property string|null $website
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KontakMitra> $kontakMitras
 * @property-read int|null $kontak_mitras_count
 * @property-read \App\Models\KriteriaMitra $kriteriaMitra
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereJenisMitra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereKabupatenKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereKodepos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereKriteriaMitraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereLingkupMitra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereNamaMitra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereNoTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereNomorIzinUsaha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mitra whereWebsite($value)
 */
	class Mitra extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $sasaran_kinerja
 * @property string|null $keterangan
 * @property string $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IndikatorSasaran> $indikatorSasarans
 * @property-read int|null $indikator_sasarans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja whereSasaranKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranKinerja whereUpdatedAt($value)
 */
	class SasaranKinerja extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_sumber_dana
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana whereNamaSumberDana($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SumberDana whereUpdatedAt($value)
 */
	class SumberDana extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_unit_kerja
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja whereNamaUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitKerja whereUpdatedAt($value)
 */
	class UnitKerja extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $roles
 * @property int $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $role
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

