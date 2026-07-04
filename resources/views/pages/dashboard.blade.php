@extends('layouts.dashboard.template')

@section('title', 'Dashboard - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
  <div class="row">
    <div class="col-xxl-12 col-md-12">
      <div class="card shadow-sm border-0">
        <div class="card-body pt-4">
          <h5 class="card-title pb-0" style="color: #157347;">Selamat Datang di SIM Kerjasama UIS</h5>
          <p class="text-secondary mb-4">Sistem Informasi Manajemen Kerjasama Universitas Ibnu Sina</p>
          
          <div class="d-flex align-items-center bg-light p-3 rounded border">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 60px; height: 60px; font-size: 24px;">
              <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="ps-3">
              <h5 class="mb-1">Halo, {{ Auth::user()->name }}!</h5>
              <span class="text-muted small">Anda masuk sebagai <span class="badge bg-success">{{ ucfirst(Auth::user()->role) }}</span>.</span>
            </div>
          </div>

          <div class="row mt-4 g-3">
            @if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
            <div class="col-md-4">
              <div class="card border shadow-none h-100">
                <div class="card-body pt-3">
                  <h6>Manajemen Pengguna</h6>
                  <p class="text-muted small">Kelola data pengguna, hak akses, password, dan status keaktifan pengguna sistem.</p>
                  <a href="{{ route('user.index') }}" class="btn btn-sm btn-uis">Kelola Pengguna</a>
                </div>
              </div>
            </div>
            @endif
            <div class="col-md-4">
              <div class="card border shadow-none h-100">
                <div class="card-body pt-3">
                  <h6>Panduan Sistem</h6>
                  <p class="text-muted small">Lihat dokumentasi panduan penggunaan Sistem Informasi Manajemen Kerjasama.</p>
                  <a href="#" class="btn btn-sm btn-outline-secondary disabled">Segera Hadir</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
@endsection
