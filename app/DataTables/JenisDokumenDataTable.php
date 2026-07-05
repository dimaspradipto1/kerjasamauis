<?php

namespace App\DataTables;

use App\Models\JenisDokumen;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisDokumenDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($item) {
                return '<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input row-checkbox" type="checkbox" value="' . $item->id . '"></div>';
            })
            ->addColumn('nama_jenis_dokumen', function ($item) {
                return '<span class="text-primary">' . e($item->nama_jenis_dokumen) . '</span>';
            })
            ->addColumn('keterangan', function ($item) {
                return $item->keterangan
                    ? '<span class="text-muted">' . e($item->keterangan) . '</span>'
                    : '-';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                $btn .= '<a href="' . route('jenis-dokumen.edit', $item->id) . '" class="btn btn-sm btn-warning text-white rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Edit"><i class="bi bi-pencil-square" style="font-size: 13px;"></i></a>';
                $btn .= '<form action="' . route('jenis-dokumen.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="bi bi-trash3-fill" style="font-size: 13px;"></i></button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['checkbox', 'action', 'nama_jenis_dokumen', 'keterangan']);
    }

    public function query(JenisDokumen $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('nama_jenis_dokumen', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-dokumen-table')
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
            Column::computed('checkbox')
                ->title('<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></div>')
                ->width('4%')
                ->addClass('text-center')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::make('nama_jenis_dokumen')
                ->title('Jenis Dokumen'),
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
        return 'JenisDokumen_' . date('YmdHis');
    }
}
