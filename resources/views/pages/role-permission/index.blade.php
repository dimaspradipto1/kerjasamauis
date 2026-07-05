@extends('layouts.dashboard.template')

@section('title', 'Atur Hak Akses - ' . $user->name . ' - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fw-bold">Atur Hak Akses</h1>
    <nav>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Hak Akses</li>
        </ol>
    </nav>
</div>

<section class="section">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
             style="border-left: 4px solid #198754 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
             style="border-left: 4px solid #dc3545 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- User Info Card --}}
    <div class="card border-0 shadow-sm mb-4 rounded-4" style="background: linear-gradient(135deg, #1a3c5e 0%, #0d6efd 100%);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4">
                <div class="user-avatar-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; border: 2px solid rgba(255,255,255,0.4);">
                    <i class="bi bi-person-fill text-white" style="font-size: 1.8rem;"></i>
                </div>
                <div>
                    <h5 class="text-white fw-bold mb-1">{{ $user->name }}</h5>
                    <span class="text-white-50 small">{{ $user->email }}</span>
                    <div class="mt-2">
                        @php
                            $roleMap = [
                                'superadmin' => ['bg-danger', 'Super Admin'],
                                'admin'      => ['bg-primary', 'Admin'],
                                'pimpinan'   => ['bg-success', 'Pimpinan'],
                                'user'       => ['bg-info text-dark', 'User'],
                            ];
                            [$badgeClass, $roleName] = $roleMap[$user->roles] ?? ['bg-secondary', ucfirst($user->roles)];
                        @endphp
                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1">{{ $roleName }}</span>
                        @if($user->is_active)
                            <span class="badge bg-light text-success rounded-pill px-3 py-1 ms-1">
                                <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-light text-danger rounded-pill px-3 py-1 ms-1">
                                <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i>Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="ms-auto text-end d-none d-md-block">
                    <div class="text-white-50 small mb-1">Konfigurasi Modul</div>
                    <div class="text-white fw-bold fs-4">{{ count($modules) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Permission Matrix Form --}}
    <form action="{{ route('role-permission.update', $user->id) }}" method="POST" id="permissionForm">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom px-4 py-3 rounded-top-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="perm-header-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Matriks Hak Akses CRUD</h6>
                            <span class="text-muted small">Centang izin yang diinginkan per modul</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success px-3" id="btnSelectAll">
                            <i class="bi bi-check2-all me-1"></i>Pilih Semua
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger px-3" id="btnClearAll">
                            <i class="bi bi-x-circle me-1"></i>Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle permission-table">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th class="ps-4 py-3 text-muted small fw-semibold text-uppercase" style="width: 35%;">
                                    Modul
                                </th>
                                <th class="text-center py-3" style="width: 16.25%;">
                                    <div class="perm-col-header">
                                        <div class="perm-col-icon perm-create">
                                            <i class="bi bi-plus-lg"></i>
                                        </div>
                                        <span class="small fw-semibold text-uppercase text-muted">Create</span>
                                        <div class="small text-muted" style="font-size: 0.7rem;">Tambah data</div>
                                    </div>
                                </th>
                                <th class="text-center py-3" style="width: 16.25%;">
                                    <div class="perm-col-header">
                                        <div class="perm-col-icon perm-read">
                                            <i class="bi bi-eye"></i>
                                        </div>
                                        <span class="small fw-semibold text-uppercase text-muted">Read</span>
                                        <div class="small text-muted" style="font-size: 0.7rem;">Lihat data</div>
                                    </div>
                                </th>
                                <th class="text-center py-3" style="width: 16.25%;">
                                    <div class="perm-col-header">
                                        <div class="perm-col-icon perm-update">
                                            <i class="bi bi-pencil"></i>
                                        </div>
                                        <span class="small fw-semibold text-uppercase text-muted">Update</span>
                                        <div class="small text-muted" style="font-size: 0.7rem;">Edit data</div>
                                    </div>
                                </th>
                                <th class="text-center py-3" style="width: 16.25%;">
                                    <div class="perm-col-header">
                                        <div class="perm-col-icon perm-delete">
                                            <i class="bi bi-trash"></i>
                                        </div>
                                        <span class="small fw-semibold text-uppercase text-muted">Delete</span>
                                        <div class="small text-muted" style="font-size: 0.7rem;">Hapus data</div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $moduleIcons = [
                                'user'            => 'bi-people-fill',
                                'mitra'           => 'bi-building',
                                'kerjasama'       => 'bi-file-earmark-text-fill',
                                'kegiatan'        => 'bi-calendar-event-fill',
                                'unit_kerja'      => 'bi-diagram-3-fill',
                                'bentuk_kegiatan' => 'bi-grid-3x3-gap-fill',
                                'sasaran_kinerja' => 'bi-bullseye',
                                'kriteria_mitra'  => 'bi-bookmark-star-fill',
                                'sumber_dana'     => 'bi-cash-stack',
                                'jenis_dokumen'   => 'bi-file-earmark-fill',
                                'laporan'         => 'bi-bar-chart-fill',
                            ];
                            @endphp
                            @foreach($matrix as $moduleKey => $moduleData)
                            <tr class="permission-row" data-module="{{ $moduleKey }}">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="module-icon-wrapper">
                                            <i class="bi {{ $moduleIcons[$moduleKey] ?? 'bi-bar-chart-fill' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $moduleData['label'] }}</div>
                                            <div class="text-muted small">{{ $moduleKey }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- CREATE --}}
                                <td class="text-center py-3">
                                    <label class="perm-toggle perm-toggle-create">
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $moduleKey }}][can_create]"
                                            value="1"
                                            class="perm-checkbox perm-create-cb"
                                            data-module="{{ $moduleKey }}"
                                            data-type="can_create"
                                            {{ $moduleData['can_create'] ? 'checked' : '' }}
                                        >
                                        <span class="perm-toggle-slider create-slider"></span>
                                    </label>
                                </td>

                                {{-- READ --}}
                                <td class="text-center py-3">
                                    <label class="perm-toggle perm-toggle-read">
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $moduleKey }}][can_read]"
                                            value="1"
                                            class="perm-checkbox perm-read-cb"
                                            data-module="{{ $moduleKey }}"
                                            data-type="can_read"
                                            {{ $moduleData['can_read'] ? 'checked' : '' }}
                                        >
                                        <span class="perm-toggle-slider read-slider"></span>
                                    </label>
                                </td>

                                {{-- UPDATE --}}
                                <td class="text-center py-3">
                                    <label class="perm-toggle perm-toggle-update">
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $moduleKey }}][can_update]"
                                            value="1"
                                            class="perm-checkbox perm-update-cb"
                                            data-module="{{ $moduleKey }}"
                                            data-type="can_update"
                                            {{ $moduleData['can_update'] ? 'checked' : '' }}
                                        >
                                        <span class="perm-toggle-slider update-slider"></span>
                                    </label>
                                </td>

                                {{-- DELETE --}}
                                <td class="text-center py-3">
                                    <label class="perm-toggle perm-toggle-delete">
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $moduleKey }}][can_delete]"
                                            value="1"
                                            class="perm-checkbox perm-delete-cb"
                                            data-module="{{ $moduleKey }}"
                                            data-type="can_delete"
                                            {{ $moduleData['can_delete'] ? 'checked' : '' }}
                                        >
                                        <span class="perm-toggle-slider delete-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer actions --}}
            <div class="card-footer bg-white border-top px-4 py-3 rounded-bottom-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="permission-summary text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="summaryText">Memuat ringkasan...</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4" id="btnSave">
                            <i class="bi bi-shield-check me-1"></i>Simpan Hak Akses
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

</section>

<style>
/* ========== HEADER ICON ========== */
.perm-header-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #1a3c5e, #0d6efd);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

/* ========== COLUMN HEADER ========== */
.perm-col-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.perm-col-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.perm-create  { background: rgba(25,135,84,0.1);  color: #198754; }
.perm-read    { background: rgba(13,110,253,0.1);  color: #0d6efd; }
.perm-update  { background: rgba(255,193,7,0.15);  color: #d39e00; }
.perm-delete  { background: rgba(220,53,69,0.1);   color: #dc3545; }

/* ========== TABLE ROWS ========== */
.permission-table tbody tr {
    border-bottom: 1px solid #f0f2f5;
    transition: background 0.15s;
}
.permission-table tbody tr:hover {
    background: #f8fafc;
}
.permission-table tbody tr:last-child {
    border-bottom: none;
}

/* ========== MODULE ICON ========== */
.module-icon-wrapper {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    font-size: 1rem;
    flex-shrink: 0;
    transition: transform 0.2s;
}
.permission-row:hover .module-icon-wrapper {
    transform: scale(1.08);
}

/* ========== TOGGLE SWITCH ========== */
.perm-toggle {
    position: relative;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    margin: 0;
}

.perm-toggle input[type="checkbox"] {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}

.perm-toggle-slider {
    position: relative;
    display: block;
    width: 46px;
    height: 24px;
    background: #e2e8f0;
    border-radius: 24px;
    transition: background 0.25s cubic-bezier(0.4,0,0.2,1);
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.perm-toggle-slider::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    background: white;
    border-radius: 50%;
    transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 1px 4px rgba(0,0,0,0.18);
}

/* Active states per type */
.perm-toggle input:checked ~ .create-slider { background: #198754; }
.perm-toggle input:checked ~ .read-slider   { background: #0d6efd; }
.perm-toggle input:checked ~ .update-slider { background: #ffc107; }
.perm-toggle input:checked ~ .delete-slider { background: #dc3545; }

.perm-toggle input:checked ~ .perm-toggle-slider::after {
    transform: translateX(22px);
}

.perm-toggle:hover .perm-toggle-slider {
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.12), 0 0 0 3px rgba(13,110,253,0.08);
}

/* ========== TABLE th ========== */
.permission-table th {
    font-weight: 600;
    letter-spacing: 0.03em;
    border-bottom: 2px solid #e9ecef;
}

/* ========== ROUNDED CARD ========== */
.rounded-4 { border-radius: 1rem !important; }
.rounded-top-4 { border-radius: 1rem 1rem 0 0 !important; }
.rounded-bottom-4 { border-radius: 0 0 1rem 1rem !important; }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allCheckboxes = document.querySelectorAll('.perm-checkbox');

    // Update summary text
    function updateSummary() {
        const total   = allCheckboxes.length;
        const checked = document.querySelectorAll('.perm-checkbox:checked').length;
        document.getElementById('summaryText').textContent =
            checked + ' dari ' + total + ' izin diaktifkan';
    }
    updateSummary();

    // On each toggle change, update summary
    allCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            // If delete/update enabled but read not → auto-enable read
            const moduleKey = this.dataset.module;
            const type      = this.dataset.type;
            if (this.checked && (type === 'can_create' || type === 'can_update' || type === 'can_delete')) {
                const readCb = document.querySelector(`.perm-checkbox[data-module="${moduleKey}"][data-type="can_read"]`);
                if (readCb && !readCb.checked) {
                    readCb.checked = true;
                }
            }
            updateSummary();
        });
    });

    // Select All
    document.getElementById('btnSelectAll').addEventListener('click', function () {
        allCheckboxes.forEach(cb => { cb.checked = true; });
        updateSummary();
    });

    // Clear All
    document.getElementById('btnClearAll').addEventListener('click', function () {
        allCheckboxes.forEach(cb => { cb.checked = false; });
        updateSummary();
    });

    // Save confirm
    document.getElementById('permissionForm').addEventListener('submit', function (e) {
        const btn = document.getElementById('btnSave');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
        btn.disabled = true;
    });
});
</script>
@endpush
