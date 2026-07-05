@extends('layouts.dashboard.template')

@section('title', 'Edit Sasaran Kinerja - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Edit Sasaran Kinerja</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item">Data Referensi</li>
      <li class="breadcrumb-item"><a href="{{ route('sasaran-kinerja.index') }}">Sasaran Kinerja</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <strong>Terdapat kesalahan:</strong>
          <ul class="mb-0 ps-3 mt-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form action="{{ route('sasaran-kinerja.update', $sasaran->id) }}" method="POST" id="formSasaran">
        @csrf
        @method('PUT')

        {{-- ══ Section 1: Informasi Sasaran Kinerja ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Sasaran Kinerja</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="mb-4">
              <label for="sasaran_kinerja" class="form-label fw-medium">
                Sasaran Kinerja <span class="text-danger">*</span>
              </label>
              <input type="text" name="sasaran_kinerja" id="sasaran_kinerja"
                class="form-control form-control-sk @error('sasaran_kinerja') is-invalid @enderror"
                value="{{ old('sasaran_kinerja', $sasaran->sasaran_kinerja) }}"
                placeholder="Masukkan Sasaran Kinerja" required>
              @error('sasaran_kinerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
              <label for="keterangan" class="form-label fw-medium">Keterangan</label>
              <textarea name="keterangan" id="keterangan"
                class="form-control form-control-sk @error('keterangan') is-invalid @enderror"
                rows="3" placeholder="Masukkan Keterangan (opsional)"
              >{{ old('keterangan', $sasaran->keterangan) }}</textarea>
              @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-1">
              <label for="level" class="form-label fw-medium">Level</label>
              <input type="text" name="level" id="level"
                class="form-control form-control-sk @error('level') is-invalid @enderror"
                value="{{ old('level', $sasaran->level) }}"
                placeholder="Masukkan Level (opsional)">
              @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

          </div>
        </div>

        {{-- ══ Section 2: Indikator Sasaran ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-bookmark-fill"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Indikator Sasaran</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div id="indikator-wrapper">
              @foreach($sasaran->indikatorSasarans as $i => $ind)
              <div class="indikator-item border rounded-3 p-4 mb-3 position-relative" data-index="{{ $i }}">
                {{-- Hidden: existing ID so controller can update vs insert --}}
                <input type="hidden" name="indikator[{{ $i }}][id]" value="{{ $ind->id }}">

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="fw-bold mb-0 indikator-title">Indikator {{ $i + 1 }}</h6>
                  <button type="button" class="btn btn-sm btn-outline-danger btn-remove-indikator {{ $loop->count <= 1 ? 'd-none' : '' }}" title="Hapus indikator ini">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-medium">Indikator <span class="text-danger">*</span></label>
                  <input type="text" name="indikator[{{ $i }}][indikator_sasaran]"
                    class="form-control form-control-sk @error('indikator.'.$i.'.indikator_sasaran') is-invalid @enderror"
                    value="{{ old('indikator.'.$i.'.indikator_sasaran', $ind->indikator_sasaran) }}"
                    placeholder="Masukkan Indikator" required>
                  @error('indikator.'.$i.'.indikator_sasaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label fw-medium">Keterangan</label>
                  <textarea name="indikator[{{ $i }}][keterangan]"
                    class="form-control form-control-sk" rows="3"
                    placeholder="Masukkan Keterangan (opsional)"
                  >{{ old('indikator.'.$i.'.keterangan', $ind->keterangan) }}</textarea>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-1">
                    <label class="form-label fw-medium">Volume</label>
                    <input type="number" name="indikator[{{ $i }}][volume]"
                      class="form-control form-control-sk"
                      value="{{ old('indikator.'.$i.'.volume', $ind->volume) }}"
                      placeholder="Masukkan Volume (opsional)" min="0">
                  </div>
                  <div class="col-md-6 mb-1">
                    <label class="form-label fw-medium">Satuan</label>
                    <input type="text" name="indikator[{{ $i }}][satuan]"
                      class="form-control form-control-sk"
                      value="{{ old('indikator.'.$i.'.satuan', $ind->satuan) }}"
                      placeholder="Masukkan Satuan (opsional)">
                  </div>
                </div>
              </div>
              @endforeach
            </div>

            <div class="text-center mt-2">
              <button type="button" id="btnTambahIndikator" class="btn btn-outline-success btn-sm px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Indikator
              </button>
            </div>

          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="{{ route('sasaran-kinerja.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-success px-4 text-white">
            <i class="bi bi-check-lg me-1"></i> Perbarui
          </button>
        </div>

      </form>
    </div>
  </div>
</section>

<style>
  .section-icon {
    width: 32px; height: 32px; background: #e8f5e9; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; color: #157347; font-size: 1rem;
  }
  .form-control-sk {
    border: 1.5px solid #dee2e6; border-radius: 8px; padding: 0.55rem 0.85rem;
    font-size: 0.9rem; transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-control-sk:focus { border-color: #157347; box-shadow: 0 0 0 0.2rem rgba(21,115,71,.12); }
  .form-control-sk::placeholder { color: #adb5bd; font-size: 0.875rem; }
  .indikator-item { background: #fafafa; }
  .indikator-item:hover { background: #f5f9f6; }
</style>
@endsection

@push('scripts')
<script>
  let indikatorCount = {{ $sasaran->indikatorSasarans->count() }};

  function makeIndikatorBlock(index) {
    return `
      <div class="indikator-item border rounded-3 p-4 mb-3 position-relative" data-index="${index}">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0 indikator-title">Indikator ${index + 1}</h6>
          <button type="button" class="btn btn-sm btn-outline-danger btn-remove-indikator" title="Hapus">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="mb-3">
          <label class="form-label fw-medium">Indikator <span class="text-danger">*</span></label>
          <input type="text" name="indikator[${index}][indikator_sasaran]"
            class="form-control form-control-sk" placeholder="Masukkan Indikator" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-medium">Keterangan</label>
          <textarea name="indikator[${index}][keterangan]"
            class="form-control form-control-sk" rows="3" placeholder="Masukkan Keterangan (opsional)"></textarea>
        </div>
        <div class="row">
          <div class="col-md-6 mb-1">
            <label class="form-label fw-medium">Volume</label>
            <input type="number" name="indikator[${index}][volume]"
              class="form-control form-control-sk" placeholder="Masukkan Volume (opsional)" min="0">
          </div>
          <div class="col-md-6 mb-1">
            <label class="form-label fw-medium">Satuan</label>
            <input type="text" name="indikator[${index}][satuan]"
              class="form-control form-control-sk" placeholder="Masukkan Satuan (opsional)">
          </div>
        </div>
      </div>`;
  }

  function renumberTitles() {
    const items = document.querySelectorAll('#indikator-wrapper .indikator-item');
    items.forEach((item, i) => {
      item.querySelector('.indikator-title').textContent = 'Indikator ' + (i + 1);
      item.querySelector('.btn-remove-indikator').classList.toggle('d-none', items.length <= 1);
    });
  }

  document.getElementById('btnTambahIndikator').addEventListener('click', function () {
    document.getElementById('indikator-wrapper')
      .insertAdjacentHTML('beforeend', makeIndikatorBlock(indikatorCount));
    indikatorCount++;
    renumberTitles();
  });

  document.getElementById('indikator-wrapper').addEventListener('click', function (e) {
    if (e.target.closest('.btn-remove-indikator')) {
      e.target.closest('.indikator-item').remove();
      renumberTitles();
    }
  });
</script>
@endpush
