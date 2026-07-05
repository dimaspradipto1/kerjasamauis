<?php

namespace App\DataTables;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KegiatanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($item) {
                return '<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input row-checkbox" type="checkbox" value="' . $item->id . '"></div>';
            })
            ->addColumn('unit_kerja_pengusul', function ($item) {
                return $item->unitKerja ? e($item->unitKerja->nama_unit_kerja) : '-';
            })
            ->addColumn('judul_kegiatan', function ($item) {
                return '<a href="' . route('kegiatan.show', $item->id) . '" class="text-primary fw-semibold">' . e($item->judul_kegiatan) . '</a>';
            })
            ->addColumn('mitra', function ($item) {
                return $item->mitra ? e($item->mitra->nama_mitra) : '-';
            })
            ->addColumn('induk_kerjasama', function ($item) {
                return $item->kerjasama ? e($item->kerjasama->judul_kerjasama) : '-';
            })
            ->addColumn('durasi_kegiatan', function ($item) {
                $awal = $item->tanggal_awal_kegiatan ? $item->tanggal_awal_kegiatan->translatedFormat('d M Y') : '-';
                $akhir = $item->tanggal_akhir_kegiatan ? $item->tanggal_akhir_kegiatan->translatedFormat('d M Y') : '-';
                return '<strong>' . $awal . '</strong> s.d. <strong>' . $akhir . '</strong>';
            })
            ->addColumn('nilai_kontrak', function ($item) {
                return number_format($item->nilai_kontrak ?? 0, 0, ',', '.');
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
                $btn .= '<a href="' . route('kegiatan.show', $item->id) . '" class="btn btn-sm btn-outline-primary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Detail"><i class="bi bi-eye" style="font-size: 13px;"></i></a>';
                
                // Delete
                $btn .= '<form action="' . route('kegiatan.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-outline-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus kegiatan ini?\')"><i class="bi bi-trash" style="font-size: 13px;"></i></button></form>';
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['checkbox', 'judul_kegiatan', 'durasi_kegiatan', 'action']);
    }

    public function query(Kegiatan $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['kerjasama', 'unitKerja', 'mitra'])->orderBy('created_at', 'desc');

        if ($mitraId = request('mitra_id')) {
            $query->where('mitra_id', $mitraId);
        }
        if ($kerjasamaId = request('kerjasama_id')) {
            $query->where('kerjasama_id', $kerjasamaId);
        }
        if ($unitKerjaId = request('unit_kerja_id')) {
            $query->where('unit_kerja_id', $unitKerjaId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kegiatan-table')
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
            Column::make('unit_kerja_pengusul')
                ->title('Unit Kerja Pengusul'),
            Column::make('judul_kegiatan')
                ->title('Judul Kegiatan'),
            Column::make('mitra')
                ->title('Mitra'),
            Column::make('induk_kerjasama')
                ->title('Induk Kerjasama'),
            Column::make('durasi_kegiatan')
                ->title('Durasi Kegiatan'),
            Column::make('nilai_kontrak')
                ->title('Nilai Kontrak (Rp)')
                ->addClass('text-end'),
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
        return 'Kegiatan_' . date('YmdHis');
    }
}
