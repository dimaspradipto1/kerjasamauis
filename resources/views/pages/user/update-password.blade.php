@extends('layouts.dashboard.template')

@section('title', 'Perbarui Password - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Perbarui Password</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Pengguna</a></li>
      <li class="breadcrumb-item active">Perbarui Password</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-body pt-4">
          <h5 class="card-title mb-2" style="color: #157347;">Form Perbarui Password</h5>
          <p class="text-secondary small mb-4">Mengubah password untuk pengguna: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>

          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{ route('user.updatePassword', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="password" class="form-label">Password Baru</label>
              <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 karakter" required autofocus>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
              <a href="{{ route('user.index') }}" class="btn btn-secondary px-4">Batal</a>
              <button type="submit" class="btn btn-uis px-4">Perbarui Password</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
