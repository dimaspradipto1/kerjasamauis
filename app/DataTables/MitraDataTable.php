<?php

namespace App\DataTables;

use App\Models\Mitra;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MitraDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('jenis_mitra', function ($item) {
                $color = $item->jenis_mitra === 'Perguruan Tinggi' ? 'bg-primary' : 'bg-info';
                return '<span class="badge ' . $color . '">' . e($item->jenis_mitra) . '</span>';
            })
            ->addColumn('nama_mitra', function ($item) {
                return '<a href="' . route('mitra.show', $item->id) . '" class="text-primary fw-semibold">' . e($item->nama_mitra) . '</a>';
            })
            ->addColumn('kriteria_mitra', function ($item) {
                return $item->kriteriaMitra ? e($item->kriteriaMitra->kriteria_mitra) : '-';
            })
            ->addColumn('lingkup_mitra', function ($item) {
                return e($item->lingkup_mitra);
            })
            ->addColumn('jumlah_kontak', function ($item) {
                return '<span class="badge bg-secondary rounded-pill">' . $item->kontakMitras->count() . ' Orang</span>';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                $btn .= '<a href="' . route('mitra.show', $item->id) . '" class="btn btn-sm btn-info text-white rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Detail"><i class="bi bi-eye-fill" style="font-size: 13px;"></i></a>';
                $btn .= '<a href="' . route('mitra.edit', $item->id) . '" class="btn btn-sm btn-warning text-white rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Edit"><i class="bi bi-pencil-square" style="font-size: 13px;"></i></a>';
                $btn .= '<form action="' . route('mitra.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus data ini? Kontak mitra terkait juga akan dihapus.\')"><i class="bi bi-trash3-fill" style="font-size: 13px;"></i></button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->setRowId('DT_RowIndex')
            ->rawColumns(['action', 'jenis_mitra', 'nama_mitra', 'jumlah_kontak']);
    }

    public function query(Mitra $model): QueryBuilder
    {
        return $model->newQuery()->with(['kriteriaMitra', 'kontakMitras'])->orderBy('nama_mitra', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('mitra-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('DT_RowIndex')
                ->title('NO')
                ->width('5%')
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),
            Column::make('jenis_mitra')
                ->title('Jenis Mitra'),
            Column::make('nama_mitra')
                ->title('Nama Mitra'),
            Column::make('kriteria_mitra')
                ->title('Kriteria Mitra'),
            Column::make('lingkup_mitra')
                ->title('Lingkup'),
            Column::computed('jumlah_kontak')
                ->title('Jumlah Kontak')
                ->addClass('text-center'),
            Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width('12%')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Mitra_' . date('YmdHis');
    }
}
