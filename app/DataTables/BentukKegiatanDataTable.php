<?php

namespace App\DataTables;

use App\Models\BentukKegiatan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BentukKegiatanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('jenis_kegiatan', function ($item) {
                $color = match ($item->jenis_kegiatan) {
                    'Pendidikan'  => 'bg-primary',
                    'Penelitian'  => 'bg-success',
                    'Pengabdian'  => 'bg-warning text-dark',
                    default       => 'bg-secondary',
                };
                return '<span class="badge rounded-pill ' . $color . '">' . $item->jenis_kegiatan . '</span>';
            })
            ->addColumn('nama_bentuk_kegiatan', function ($item) {
                return $item->nama_bentuk_kegiatan;
            })
            ->addColumn('keterangan', function ($item) {
                return $item->keterangan ?? '-';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                $btn .= '<a href="' . route('bentuk-kegiatan.edit', $item->id) . '" class="btn btn-sm btn-warning text-white rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Edit"><i class="bi bi-pencil-square" style="font-size: 13px;"></i></a>';
                $btn .= '<form action="' . route('bentuk-kegiatan.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="bi bi-trash3-fill" style="font-size: 13px;"></i></button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->setRowId('DT_RowIndex')
            ->rawColumns(['action', 'jenis_kegiatan']);
    }

    public function query(BentukKegiatan $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('jenis_kegiatan')->orderBy('nama_bentuk_kegiatan');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('bentuk-kegiatan-table')
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
            Column::make('jenis_kegiatan')
                ->title('Jenis Kegiatan'),
            Column::make('nama_bentuk_kegiatan')
                ->title('Nama Bentuk Kegiatan'),
            Column::make('keterangan')
                ->title('Keterangan'),
            Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width('10%')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'BentukKegiatan_' . date('YmdHis');
    }
}
