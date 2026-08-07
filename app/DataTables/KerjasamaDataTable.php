<?php

namespace App\DataTables;

use App\Models\Kerjasama;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KerjasamaDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($item) {
                return '<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input row-checkbox" type="checkbox" value="' . $item->id . '"></div>';
            })
            ->addColumn('unit_kerja', function ($item) {
                $rawUnits = $item->unitKerjas->isNotEmpty()
                    ? $item->unitKerjas->pluck('nama_unit_kerja')->toArray()
                    : ($item->unitKerja ? [$item->unitKerja->nama_unit_kerja] : []);

                $units = [];
                foreach ($rawUnits as $u) {
                    if (str_contains($u, ',')) {
                        foreach (array_map('trim', explode(',', $u)) as $p) {
                            if ($p !== '') {
                                $units[] = $p;
                            }
                        }
                    } else {
                        if (trim($u) !== '') {
                            $units[] = trim($u);
                        }
                    }
                }

                if (empty($units)) {
                    return '-';
                }

                $html = '<ol class="mb-0 text-dark unit-kerja-list" style="margin: 0; padding-left: 1.2rem; list-style-type: decimal; line-height: 1.4;">';
                foreach ($units as $u) {
                    $html .= '<li style="margin-bottom: 2px;">' . e($u) . '</li>';
                }
                $html .= '</ol>';

                return $html;
            })
            ->addColumn('judul_kerjasama', function ($item) {
                return '<a href="' . route('kerjasama.show', $item->id) . '" class="text-primary fw-semibold">' . e($item->judul_kerjasama) . '</a>';
            })
            ->addColumn('mitra', function ($item) {
                return $item->mitra ? e($item->mitra->nama_mitra) : '-';
            })
            ->addColumn('jenis_dokumen', function ($item) {
                return $item->jenisDokumen ? e($item->jenisDokumen->nama_jenis_dokumen) : '-';
            })
            ->addColumn('nomor_dokumen_kerjasama', function ($item) {
                return e($item->nomor_dokumen_kerjasama);
            })
            ->addColumn('durasi_kerjasama', function ($item) {
                $awal = $item->tanggal_waktu_berlaku ? $item->tanggal_waktu_berlaku->translatedFormat('d M Y') : '-';
                $akhir = $item->tanggal_akhir_berlaku ? $item->tanggal_akhir_berlaku->translatedFormat('d M Y') : '-';
                return '<span class="text-nowrap"><strong>' . $awal . '</strong> s.d. <strong>' . $akhir . '</strong></span>';
            })
            ->addColumn('status_kerjasama', function ($item) {
                if ($item->status_kerjasama === 'Aktif') {
                    return '<span class="badge bg-success-light text-success border border-success-subtle px-2 py-1" style="font-weight: 500;"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>';
                }
                return '<span class="badge bg-secondary-light text-secondary border border-secondary-subtle px-2 py-1" style="font-weight: 500;">' . e($item->status_kerjasama) . '</span>';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                
                // View file
                $files = is_array($item->url_file) ? $item->url_file : (empty($item->url_file) ? [] : (json_decode($item->url_file, true) ?: [$item->url_file]));
                if (count($files) === 1) {
                    $btn .= '<a href="' . asset('storage/' . $files[0]) . '" target="_blank" class="btn btn-sm btn-outline-secondary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Lihat File (' . e(basename($files[0])) . ')"><i class="bi bi-file-earmark-text" style="font-size: 13px;"></i></a>';
                } elseif (count($files) > 1) {
                    $btn .= '<div class="dropdown d-inline-block">';
                    $btn .= '<button class="btn btn-sm btn-outline-secondary rounded shadow-sm d-flex align-items-center justify-content-center dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 30px; height: 30px; padding: 0 6px;" title="Lihat File (' . count($files) . ' file)"><i class="bi bi-file-earmark-text me-1" style="font-size: 13px;"></i><span class="badge bg-secondary" style="font-size: 9px;">' . count($files) . '</span></button>';
                    $btn .= '<ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 12px;">';
                    foreach ($files as $f) {
                        $btn .= '<li><a class="dropdown-item text-truncate" style="max-width: 220px;" href="' . asset('storage/' . $f) . '" target="_blank" title="' . e(basename($f)) . '"><i class="bi bi-file-earmark-arrow-down me-1 text-primary"></i> ' . e(basename($f)) . '</a></li>';
                    }
                    $btn .= '</ul></div>';
                } else {
                    $btn .= '<button class="btn btn-sm btn-outline-secondary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Tidak ada file" disabled><i class="bi bi-file-earmark-text text-muted" style="font-size: 13px;"></i></button>';
                }
                
                // Detail
                $btn .= '<a href="' . route('kerjasama.show', $item->id) . '" class="btn btn-sm btn-outline-primary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Detail"><i class="bi bi-eye" style="font-size: 13px;"></i></a>';
                
                // Delete
                $btn .= '<form action="' . route('kerjasama.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-outline-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus kerjasama ini?\')"><i class="bi bi-trash" style="font-size: 13px;"></i></button></form>';
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['checkbox', 'unit_kerja', 'judul_kerjasama', 'durasi_kerjasama', 'status_kerjasama', 'action']);
    }

    public function query(Kerjasama $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['jenisDokumen', 'mitra', 'unitKerja', 'unitKerjas'])->orderBy('created_at', 'desc');

        if ($mitraId = request('mitra_id')) {
            $query->where('mitra_id', $mitraId);
        }
        if ($statusKerjasama = request('status_kerjasama')) {
            $query->where('status_kerjasama', $statusKerjasama);
        }
        if ($filterKadaluwarsa = request('filter_kadaluwarsa')) {
            $today = now()->toDateString();
            if ($filterKadaluwarsa === '1_week') {
                $query->whereBetween('tanggal_akhir_berlaku', [$today, now()->addWeek()->toDateString()]);
            } elseif ($filterKadaluwarsa === '1_month') {
                $query->whereBetween('tanggal_akhir_berlaku', [$today, now()->addMonth()->toDateString()]);
            } elseif ($filterKadaluwarsa === '3_months') {
                $query->whereBetween('tanggal_akhir_berlaku', [$today, now()->addMonths(3)->toDateString()]);
            } elseif ($filterKadaluwarsa === '6_months') {
                $query->whereBetween('tanggal_akhir_berlaku', [$today, now()->addMonths(6)->toDateString()]);
            } elseif ($filterKadaluwarsa === '1_year') {
                $query->whereBetween('tanggal_akhir_berlaku', [$today, now()->addYear()->toDateString()]);
            }
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kerjasama-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"table-responsive"t><"d-flex justify-content-between align-items-center mt-4 pt-4 border-top mb-2"<"dataTables_info_wrapper"i><"dataTables_paginate_wrapper"p>>')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->title('<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div>')
                ->width('35px')
                ->addClass('text-center')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::make('unit_kerja')
                ->title('Unit Kerja')
                ->width('280px'),
            Column::make('judul_kerjasama')
                ->title('Judul Kerjasama')
                ->width('220px'),
            Column::make('mitra')
                ->title('Mitra')
                ->width('180px'),
            Column::make('jenis_dokumen')
                ->title('Jenis Dokumen')
                ->width('140px'),
            Column::make('nomor_dokumen_kerjasama')
                ->title('Nomor Dokumen Kerjasama')
                ->width('150px'),
            Column::make('durasi_kerjasama')
                ->title('Durasi Kerjasama')
                ->width('170px'),
            Column::make('status_kerjasama')
                ->title('Status Kerjasama')
                ->width('100px')
                ->addClass('text-center'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width('100px')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Kerjasama_' . date('YmdHis');
    }
}
