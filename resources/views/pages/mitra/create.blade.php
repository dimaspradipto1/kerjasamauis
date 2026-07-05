@extends('layouts.dashboard.template')

@section('title', 'Tambah Mitra - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Tambah Mitra</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('mitra.index') }}">Mitra</a></li>
      <li class="breadcrumb-item active">Tambah</li>
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

      <form action="{{ route('mitra.store') }}" method="POST" id="formMitra">
        @csrf

        {{-- ══ Section 1: Informasi Mitra ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-building"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Mitra</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="mb-4">
              <label class="form-label fw-semibold d-block">Jenis Mitra <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_mitra" id="jenis_pt" value="Perguruan Tinggi" {{ old('jenis_mitra', 'Perguruan Tinggi') === 'Perguruan Tinggi' ? 'checked' : '' }} required>
                <label class="form-check-label" for="jenis_pt">Perguruan Tinggi</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_mitra" id="jenis_non_pt" value="Non Perguruan Tinggi" {{ old('jenis_mitra') === 'Non Perguruan Tinggi' ? 'checked' : '' }}>
                <label class="form-check-label" for="jenis_non_pt">Non Perguruan Tinggi</label>
              </div>
            </div>

            <div class="mb-4">
              <label for="nama_mitra" class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
              <input type="text" name="nama_mitra" id="nama_mitra" class="form-control form-control-m" value="{{ old('nama_mitra') }}" placeholder="Masukkan Nama Mitra" required>
            </div>

            <div class="mb-4">
              <label for="kriteria_mitra_id" class="form-label fw-semibold">Kriteria Mitra <span class="text-danger">*</span></label>
              <select name="kriteria_mitra_id" id="kriteria_mitra_id" class="form-select form-control-m" required>
                <option value="" disabled selected>Pilih Kriteria Mitra</option>
                @foreach($kriteriaMitra as $kriteria)
                  <option value="{{ $kriteria->id }}" {{ old('kriteria_mitra_id') == $kriteria->id ? 'selected' : '' }}>{{ $kriteria->kriteria_mitra }}</option>
                @endforeach
              </select>
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="nomor_izin_usaha" class="form-label fw-semibold">Nomor Surat Izin Usaha / Perguruan Tinggi</label>
                <input type="text" name="nomor_izin_usaha" id="nomor_izin_usaha" class="form-control form-control-m" value="{{ old('nomor_izin_usaha') }}" placeholder="(opsional)">
              </div>
              <div class="col-md-6">
                <label for="npwp" class="form-label fw-semibold">NPWP Mitra</label>
                <input type="text" name="npwp" id="npwp" class="form-control form-control-m" value="{{ old('npwp') }}" placeholder="(opsional)">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold d-block">Lingkup Mitra <span class="text-danger">*</span></label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="lingkup_mitra" id="lingkup_lokal" value="Lokal" {{ old('lingkup_mitra', 'Lokal') === 'Lokal' ? 'checked' : '' }} required>
                <label class="form-check-label" for="lingkup_lokal">Lokal</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="lingkup_mitra" id="lingkup_regional" value="Regional" {{ old('lingkup_mitra') === 'Regional' ? 'checked' : '' }}>
                <label class="form-check-label" for="lingkup_regional">Regional</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="lingkup_mitra" id="lingkup_nasional" value="Nasional" {{ old('lingkup_mitra') === 'Nasional' ? 'checked' : '' }}>
                <label class="form-check-label" for="lingkup_nasional">Nasional</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="lingkup_mitra" id="lingkup_internasional" value="Internasional" {{ old('lingkup_mitra') === 'Internasional' ? 'checked' : '' }}>
                <label class="form-check-label" for="lingkup_internasional">Internasional</label>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="provinsi" class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                <select name="provinsi" id="provinsi" class="form-select form-control-m" required>
                  <option value="" disabled selected>Pilih Provinsi</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="kabupaten_kota" class="form-label fw-semibold">Kota / Kabupaten <span class="text-danger">*</span></label>
                <select name="kabupaten_kota" id="kabupaten_kota" class="form-select form-control-m" required disabled>
                  <option value="" disabled selected>Pilih Kota / Kabupaten</option>
                </select>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="kecamatan" class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                <select name="kecamatan" id="kecamatan" class="form-select form-control-m" required disabled>
                  <option value="" disabled selected>Pilih Kecamatan</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="kodepos" class="form-label fw-semibold">Kode POS</label>
                <input type="text" name="kodepos" id="kodepos" class="form-control form-control-m" value="{{ old('kodepos') }}" placeholder="Masukkan Kode POS (opsional)">
              </div>
            </div>

            <div class="mb-4">
              <label for="alamat" class="form-label fw-semibold">Alamat</label>
              <textarea name="alamat" id="alamat" class="form-control form-control-m" rows="3" placeholder="Masukkan Alamat (opsional)">{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-4">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" name="email" id="email" class="form-control form-control-m" value="{{ old('email') }}" placeholder="Masukkan Email (opsional)">
            </div>

            <div class="mb-4">
              <label for="no_telp" class="form-label fw-semibold">Nomor Telepon</label>
              <input type="text" name="no_telp" id="no_telp" class="form-control form-control-m" value="{{ old('no_telp') }}" placeholder="Masukkan Nomor Telepon (opsional)">
            </div>

            <div class="mb-2">
              <label for="website" class="form-label fw-semibold">Link Website</label>
              <input type="text" name="website" id="website" class="form-control form-control-m" value="{{ old('website') }}" placeholder="Masukkan Link Website (opsional)">
            </div>

          </div>
        </div>

        {{-- ══ Section 2: Informasi Kontak Mitra ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-person-lines-fill"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Kontak Mitra</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div id="kontak-wrapper">
              {{-- Kontak pertama --}}
              <div class="kontak-item border rounded-3 p-4 mb-3 position-relative" data-index="0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="fw-bold mb-0 kontak-title text-success">Kontak 1</h6>
                  <button type="button" class="btn btn-danger btn-sm btn-remove-kontak d-none" title="Hapus kontak ini">
                    <i class="bi bi-trash3-fill me-1"></i> Hapus
                  </button>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-medium">Nama Kontak <span class="text-danger">*</span></label>
                    <input type="text" name="kontak[0][nama_kontak]" class="form-control form-control-m" placeholder="Masukkan Nama Kontak" required value="{{ old('kontak.0.nama_kontak') }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="kontak[0][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan" required value="{{ old('kontak.0.jabatan') }}">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-medium">Nomor Handphone <span class="text-danger">*</span></label>
                    <input type="text" name="kontak[0][nomor_handphone]" class="form-control form-control-m" placeholder="Masukkan Nomor Handphone" required value="{{ old('kontak.0.nomor_handphone') }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                    <input type="email" name="kontak[0][email]" class="form-control form-control-m" placeholder="Masukkan Email" required value="{{ old('kontak.0.email') }}">
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-2">
              <button type="button" id="btnTambahKontak" class="btn btn-outline-success btn-sm px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kontak
              </button>
            </div>

          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="{{ route('mitra.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-success px-4 text-white">
            <i class="bi bi-check-lg me-1"></i> Simpan
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
  .form-control-m {
    border: 1.5px solid #dee2e6; border-radius: 8px;
    padding: 0.55rem 0.85rem; font-size: 0.9rem;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-control-m:focus { border-color: #157347; box-shadow: 0 0 0 0.2rem rgba(21,115,71,.12); }
  .form-control-m::placeholder { color: #adb5bd; font-size: 0.875rem; }
  .kontak-item { background: #fafafa; }
  .kontak-item:hover { background: #f5f9f6; }

  /* Select2 Styling Overrides */
  .select2-container .select2-selection--single {
    height: 44px !important;
    border: 1.5px solid #dee2e6 !important;
    border-radius: 8px !important;
    padding: 0.55rem 0.85rem !important;
    font-size: 0.9rem !important;
    display: flex !important;
    align-items: center !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #212529 !important;
    padding-left: 0 !important;
    line-height: normal !important;
  }
  .select2-container--default .select2-selection--single:focus,
  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #157347 !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.2rem rgba(21, 115, 71, 0.12) !important;
  }
  .select2-dropdown {
    border: 1.5px solid #dee2e6 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    overflow: hidden !important;
  }
</style>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize Select2
    $('#provinsi').select2({
      placeholder: "Pilih Provinsi",
      allowClear: true
    });
    $('#kabupaten_kota').select2({
      placeholder: "Pilih Kota / Kabupaten",
      allowClear: true
    });
    $('#kecamatan').select2({
      placeholder: "Pilih Kecamatan",
      allowClear: true
    });

    const provinceSelect = document.getElementById('provinsi');
    const regencySelect = document.getElementById('kabupaten_kota');
    const districtSelect = document.getElementById('kecamatan');

    // Fetch Provinces
    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
      .then(response => response.json())
      .then(provinces => {
        provinces.forEach(prov => {
          const option = document.createElement('option');
          option.value = prov.name;
          option.textContent = prov.name;
          option.setAttribute('data-id', prov.id);
          provinceSelect.appendChild(option);
        });
        // Trigger select2 update
        $('#provinsi').trigger('change');
      })
      .catch(err => console.error('Error fetching provinces:', err));

    // Province change event via jQuery/Select2
    $('#provinsi').on('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      if (!selectedOption) return;
      const provId = selectedOption.getAttribute('data-id');

      // Reset regency and district
      regencySelect.innerHTML = '<option value="" disabled selected>Pilih Kota / Kabupaten</option>';
      districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
      regencySelect.disabled = true;
      districtSelect.disabled = true;
      
      // Update select2 instances
      $('#kabupaten_kota').trigger('change');
      $('#kecamatan').trigger('change');

      if (provId) {
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
          .then(response => response.json())
          .then(regencies => {
            regencies.forEach(reg => {
              const option = document.createElement('option');
              option.value = reg.name;
              option.textContent = reg.name;
              option.setAttribute('data-id', reg.id);
              regencySelect.appendChild(option);
            });
            regencySelect.disabled = false;
            $('#kabupaten_kota').trigger('change');
          })
          .catch(err => console.error('Error fetching regencies:', err));
      }
    });

    // Regency change event via jQuery/Select2
    $('#kabupaten_kota').on('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      if (!selectedOption) return;
      const regId = selectedOption.getAttribute('data-id');

      // Reset district
      districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
      districtSelect.disabled = true;
      $('#kecamatan').trigger('change');

      if (regId) {
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regId}.json`)
          .then(response => response.json())
          .then(districts => {
            districts.forEach(dist => {
              const option = document.createElement('option');
              option.value = dist.name;
              option.textContent = dist.name;
              option.setAttribute('data-id', dist.id);
              districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
            $('#kecamatan').trigger('change');
          })
          .catch(err => console.error('Error fetching districts:', err));
      }
    });
  });

  // ══ Dynamic Contacts ══
  let kontakCount = 1;

  function makeKontakBlock(index) {
    return `
      <div class="kontak-item border rounded-3 p-4 mb-3 position-relative" data-index="${index}">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0 kontak-title text-success">Kontak ${index + 1}</h6>
          <button type="button" class="btn btn-danger btn-sm btn-remove-kontak" title="Hapus kontak ini">
            <i class="bi bi-trash3-fill me-1"></i> Hapus
          </button>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 mb-3 mb-md-0">
            <label class="form-label fw-medium">Nama Kontak <span class="text-danger">*</span></label>
            <input type="text" name="kontak[${index}][nama_kontak]" class="form-control form-control-m" placeholder="Masukkan Nama Kontak" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Jabatan <span class="text-danger">*</span></label>
            <input type="text" name="kontak[${index}][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3 mb-md-0">
            <label class="form-label fw-medium">Nomor Handphone <span class="text-danger">*</span></label>
            <input type="text" name="kontak[${index}][nomor_handphone]" class="form-control form-control-m" placeholder="Masukkan Nomor Handphone" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
            <input type="email" name="kontak[${index}][email]" class="form-control form-control-m" placeholder="Masukkan Email" required>
          </div>
        </div>
      </div>`;
  }

  function renumberKontaks() {
    const items = document.querySelectorAll('#kontak-wrapper .kontak-item');
    items.forEach((item, i) => {
      item.querySelector('.kontak-title').textContent = 'Kontak ' + (i + 1);
      item.querySelector('.btn-remove-kontak').classList.toggle('d-none', items.length <= 1);
    });
  }

  document.getElementById('btnTambahKontak').addEventListener('click', function() {
    document.getElementById('kontak-wrapper').insertAdjacentHTML('beforeend', makeKontakBlock(kontakCount));
    kontakCount++;
    renumberKontaks();
  });

  document.getElementById('kontak-wrapper').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-kontak')) {
      e.target.closest('.kontak-item').remove();
      renumberKontaks();
    }
  });
</script>
@endpush
