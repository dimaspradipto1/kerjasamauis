@extends('layouts.dashboard.template')

@section('title', 'Backup Database & Storage - SIM Kerjasama UIS')

@section('content')

    {{-- Header Halaman --}}
    <div class="pagetitle mb-4" style="margin-top: 6px; margin-bottom: 28px !important;">
        <h1 class="fw-bold" style="color: #012970; font-size: 24px; margin-bottom: 6px;">Backup Database & Storage</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 14px;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Pengaturan Sistem</li>
                <li class="breadcrumb-item active">Backup Database & Storage</li>
            </ol>
        </nav>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
             style="border-left: 4px solid #198754 !important; background-color: #f0fdf4; border-radius: 8px;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span class="text-success fw-medium">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
             style="border-left: 4px solid #dc3545 !important; background-color: #fef2f2; border-radius: 8px;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <span class="text-danger fw-medium">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
             style="border-left: 4px solid #dc3545 !important; background-color: #fef2f2; border-radius: 8px;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                <span class="text-danger fw-bold">Terjadi kesalahan validasi:</span>
            </div>
            <ul class="mb-0 text-danger ps-4 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section" style="margin-top: 10px; margin-bottom: 30px;">
        <div class="row g-4">

            {{-- ======================================================== --}}
            {{-- CARD 1: EKSPOR & IMPOR DATABASE --}}
            {{-- ======================================================== --}}
            <div class="col-xl-6 col-lg-12">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #ffffff;">
                    <div class="card-body d-flex flex-column justify-content-between" style="padding: 32px 30px 28px 30px !important;">
                        <div>
                            {{-- Header Card --}}
                            <div class="d-flex align-items-center gap-3 mb-4" style="margin-top: 2px;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm"
                                     style="width: 56px; height: 56px; background-color: #27572d; color: #ffffff;">
                                    {{-- Database Icon (SVG inline agar pasti tampil) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.18rem;">Ekspor & Impor Database</h5>
                                    <p class="text-muted small mb-0" style="font-size: 0.875rem; line-height: 1.4;">
                                        Unduh atau pulihkan struktur tabel dan data database aplikasi sebagai berkas SQL.
                                    </p>
                                </div>
                            </div>

                            {{-- Metadata Table --}}
                            <div class="table-responsive mb-4" style="margin-top: 8px;">
                                <table class="table table-borderless table-sm mb-0" style="font-size: 0.9rem;">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-semibold ps-0 py-2" style="width: 140px; vertical-align: middle;">Koneksi</td>
                                            <td class="py-2">
                                                <span class="badge border" style="background-color: #f8fafc; color: #334155; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.5px; padding: 5px 9px;">
                                                    {{ strtoupper($connection ?? 'MYSQL') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold ps-0 py-2" style="vertical-align: middle;">Database</td>
                                            <td class="py-2">
                                                <span class="fw-bold" style="color: #e11d48; font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.95rem;">
                                                    {{ $databaseName }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold ps-0 py-2" style="vertical-align: middle;">Isi backup</td>
                                            <td class="py-2" style="color: #4b5563;">Struktur dan data semua tabel MySQL</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Alert Info Administrator --}}
                            <div class="alert border-0 d-flex align-items-start gap-2 py-3 px-3 mb-4"
                                 style="background-color: #e0f2fe; color: #0284c7; border-radius: 8px; font-size: 0.85rem; line-height: 1.5; margin-top: 10px;">
                                <i class="bi bi-shield-shaded fs-5 flex-shrink-0" style="color: #0369a1;"></i>
                                <div style="color: #0c4a6e;">
                                    Hanya administrator yang dapat mengunduh dan memulihkan database. Simpan berkas SQL di lokasi aman karena berisi seluruh data aplikasi.
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-2 pt-2">
                            <a href="{{ route('backup.database.download') }}"
                               class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white fw-semibold shadow-sm"
                               style="background-color: #27572d; border-color: #27572d; border-radius: 6px; font-size: 0.9rem;">
                                <i class="bi bi-download"></i> Unduh Backup SQL
                            </a>
                            <button type="button"
                                    class="btn d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold"
                                    data-bs-toggle="modal" data-bs-target="#modalImportDatabase"
                                    style="border: 1.5px solid #6366f1; color: #4f46e5; background: #ffffff; border-radius: 6px; font-size: 0.9rem;">
                                <i class="bi bi-upload"></i> Import / Pulihkan Database
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- CARD 2: BACKUP & IMPOR STORAGE PUBLIC --}}
            {{-- ======================================================== --}}
            <div class="col-xl-6 col-lg-12">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #ffffff;">
                    <div class="card-body d-flex flex-column justify-content-between" style="padding: 32px 30px 28px 30px !important;">
                        <div>
                            {{-- Header Card --}}
                            <div class="d-flex align-items-center gap-3 mb-4" style="margin-top: 2px;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm"
                                     style="width: 56px; height: 56px; background-color: #eaf5ea; color: #27572d;">
                                    <i class="bi bi-folder-symlink" style="font-size: 28px; line-height: 1;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.18rem;">Backup & Impor Storage Public</h5>
                                    <p class="text-muted small mb-0" style="font-size: 0.875rem; line-height: 1.4;">
                                        Arsipkan atau pulihkan semua file pada folder <code style="color: #e11d48; background-color: #ffe4e6; padding: 2px 7px; border-radius: 4px; font-size: 0.85rem;">storage/app/public</code> ke dalam berkas ZIP.
                                    </p>
                                </div>
                            </div>

                            {{-- Spacer yang menyamakan tinggi konten dengan Card 1 agar sejajar sempurna --}}
                            <div class="d-none d-xl-block" style="height: 110px;"></div>

                            {{-- Alert Warning Storage --}}
                            <div class="alert border-0 d-flex align-items-start gap-2 py-3 px-3 mb-4"
                                 style="background-color: #fef9c3; color: #854d0e; border-radius: 8px; font-size: 0.85rem; line-height: 1.5; margin-top: 10px;">
                                <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0" style="color: #ca8a04;"></i>
                                <div style="color: #713f12;">
                                    Backup storage memuat seluruh berkas lampiran, SK, dan dokumen publik aplikasi.
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-2 pt-2">
                            <a href="{{ route('backup.storage.download') }}"
                               class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white fw-semibold shadow-sm"
                               style="background-color: #27572d; border-color: #27572d; border-radius: 6px; font-size: 0.9rem;">
                                <i class="bi bi-download"></i> Unduh Backup Storage ZIP
                            </a>
                            <button type="button"
                                    class="btn d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold"
                                    data-bs-toggle="modal" data-bs-target="#modalImportStorage"
                                    style="border: 1.5px solid #27572d; color: #27572d; background: #ffffff; border-radius: 6px; font-size: 0.9rem;">
                                <i class="bi bi-upload"></i> Import / Pulihkan Storage
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- MODAL IMPORT DATABASE --}}
    {{-- ======================================================== --}}
    <div class="modal fade" id="modalImportDatabase" tabindex="-1" aria-labelledby="modalImportDatabaseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                <form id="formImportDatabase" action="{{ route('backup.database.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                 style="width: 40px; height: 40px; background-color: #27572d;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                            </div>
                            <h5 class="modal-title fw-bold" id="modalImportDatabaseLabel" style="color: #1f2937;">Import / Pulihkan Database</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-warning border-0 small d-flex align-items-start gap-2 mb-3"
                             style="background-color: #fef3c7; color: #92400e; border-radius: 8px;">
                            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 mt-n1"></i>
                            <div>
                                <strong>PERHATIAN:</strong> Memulihkan database akan mengeksekusi berkas SQL dan menimpa struktur atau data tabel yang ada saat ini. Pastikan Anda telah membuat backup terbaru sebelum melanjutkan.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sql_file" class="form-label fw-semibold small text-muted">Pilih Berkas SQL Backup (.sql) <span class="text-danger">*</span></label>
                            <input type="file" name="sql_file" id="sql_file" class="form-control" accept=".sql" required style="border-radius: 8px;">
                            <div class="form-text small">Maksimum ukuran berkas menyesuaikan konfigurasi PHP server.</div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="confirm_db_restore" required>
                            <label class="form-check-label small text-muted" for="confirm_db_restore">
                                Saya mengerti dan yakin ingin memulihkan database aplikasi dari berkas SQL ini.
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                        <button type="submit" class="btn text-white px-3 fw-semibold" style="background-color: #27572d; border-radius: 6px;">
                            <i class="bi bi-arrow-repeat me-1"></i> Mulai Pulihkan Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL IMPORT STORAGE --}}
    {{-- ======================================================== --}}
    <div class="modal fade" id="modalImportStorage" tabindex="-1" aria-labelledby="modalImportStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                <form id="formImportStorage" action="{{ route('backup.storage.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; background-color: #eaf5ea; color: #27572d;">
                                <i class="bi bi-folder-symlink fs-5"></i>
                            </div>
                            <h5 class="modal-title fw-bold" id="modalImportStorageLabel" style="color: #1f2937;">Import / Pulihkan Storage Public</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 small d-flex align-items-start gap-2 mb-3"
                             style="background-color: #e0f2fe; color: #0284c7; border-radius: 8px;">
                            <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 mt-n1"></i>
                            <div>
                                Berkas ZIP akan diekstrak langsung ke folder <code style="color: #e11d48;">storage/app/public</code>. Berkas dengan nama dan lokasi yang sama akan diperbarui secara otomatis.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="zip_file" class="form-label fw-semibold small text-muted">Pilih Berkas ZIP Backup (.zip) <span class="text-danger">*</span></label>
                            <input type="file" name="zip_file" id="zip_file" class="form-control" accept=".zip" required style="border-radius: 8px;">
                            <div class="form-text small">Hanya berkas format .zip yang diperbolehkan.</div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="confirm_storage_restore" required>
                            <label class="form-check-label small text-muted" for="confirm_storage_restore">
                                Saya mengerti berkas dalam storage public akan diekstrak/ditimpa.
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Batal</button>
                        <button type="submit" class="btn text-white px-3 fw-semibold" style="background-color: #27572d; border-radius: 6px;">
                            <i class="bi bi-box-arrow-in-down me-1"></i> Mulai Ekstrak & Pulihkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Konfirmasi pemulihan database dengan SweetAlert2
        const formImportDb = document.getElementById('formImportDatabase');
        if (formImportDb) {
            formImportDb.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Pulihkan Database?',
                    text: "Apakah Anda yakin ingin memulihkan database dari berkas SQL yang dipilih? Data yang ada dapat ditimpa.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#27572d',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Pulihkan Sekarang!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses Pemulihan...',
                            text: 'Mohon tunggu beberapa saat.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formImportDb.submit();
                    }
                });
            });
        }

        // Konfirmasi pemulihan storage dengan SweetAlert2
        const formImportStorage = document.getElementById('formImportStorage');
        if (formImportStorage) {
            formImportStorage.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Pulihkan Storage Public?',
                    text: "Apakah Anda yakin ingin mengekstrak berkas ZIP ke storage/app/public? File yang sama akan ditimpa.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#27572d',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ekstrak Sekarang!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengekstrak Berkas...',
                            text: 'Mohon tunggu proses ekstraksi berlangsung.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formImportStorage.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
