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
                return $item->unitKerja ? e($item->unitKerja->nama_unit_kerja) : '-';
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
                return '<strong>' . $awal . '</strong> s.d. <strong>' . $akhir . '</strong>';
            })
            ->addColumn('status_kerjasama', function ($item) {
                if ($item->status_kerjasama === 'Aktif') {
                    return '<span class="badge bg-success-light text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>';
                }
                return '<span class="badge bg-secondary-light text-secondary border border-secondary-subtle px-2 py-1">' . e($item->status_kerjasama) . '</span>';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                
                // View file
                if ($item->url_file) {
                    $btn .= '<a href="' . asset('storage/' . $item->url_file) . '" target="_blank" class="btn btn-sm btn-outline-secondary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Lihat File"><i class="bi bi-file-earmark-text" style="font-size: 13px;"></i></a>';
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
            ->rawColumns(['checkbox', 'judul_kerjasama', 'durasi_kerjasama', 'status_kerjasama', 'action']);
    }

    public function query(Kerjasama $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['jenisDokumen', 'mitra', 'unitKerja'])->orderBy('created_at', 'desc');

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
            ->dom('Brtip')
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
                ->width('4%')
                ->addClass('text-center')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::make('unit_kerja')
                ->title('Unit Kerja'),
            Column::make('judul_kerjasama')
                ->title('Judul Kerjasama'),
            Column::make('mitra')
                ->title('Mitra'),
            Column::make('jenis_dokumen')
                ->title('Jenis Dokumen'),
            Column::make('nomor_dokumen_kerjasama')
                ->title('Nomor Dokumen Kerjasama'),
            Column::make('durasi_kerjasama')
                ->title('Durasi Kerjasama'),
            Column::make('status_kerjasama')
                ->title('Status Kerjasama')
                ->addClass('text-center'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width('12%')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Kerjasama_' . date('YmdHis');
    }
}
