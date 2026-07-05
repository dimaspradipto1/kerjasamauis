<?php

namespace App\DataTables;

use App\Models\SasaranKinerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SasaranKinerjaDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($item) {
                return '<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input row-checkbox" type="checkbox" value="' . $item->id . '"></div>';
            })
            ->addColumn('sasaran_kinerja', function ($item) {
                return '<a href="' . route('sasaran-kinerja.show', $item->id) . '" class="text-primary fw-medium">'
                    . e($item->sasaran_kinerja) . '</a>';
            })
            ->addColumn('keterangan', function ($item) {
                return $item->keterangan
                    ? '<span class="text-muted">' . e($item->keterangan) . '</span>'
                    : '-';
            })
            ->addColumn('level', function ($item) {
                $color = match ($item->level) {
                    'Prioritas'           => 'text-success fw-semibold',
                    'Prioritas Kementrian' => 'text-primary fw-semibold',
                    default               => 'text-secondary',
                };
                return '<span class="' . $color . '">' . e($item->level) . '</span>';
            })
            ->addColumn('jumlah_indikator', function ($item) {
                $count = $item->indikatorSasarans->count();
                return '<span class="badge bg-secondary rounded-pill">' . $count . ' Indikator</span>';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                $btn .= '<a href="' . route('sasaran-kinerja.show', $item->id) . '" class="btn btn-sm btn-info text-white rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Detail"><i class="bi bi-eye-fill" style="font-size: 13px;"></i></a>';
                $btn .= '<a href="' . route('sasaran-kinerja.edit', $item->id) . '" class="btn btn-sm btn-warning text-white rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Edit"><i class="bi bi-pencil-square" style="font-size: 13px;"></i></a>';
                $btn .= '<form action="' . route('sasaran-kinerja.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus data ini? Semua indikator terkait juga akan dihapus.\')"><i class="bi bi-trash3-fill" style="font-size: 13px;"></i></button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['checkbox', 'action', 'sasaran_kinerja', 'keterangan', 'level', 'jumlah_indikator']);
    }

    public function query(SasaranKinerja $model): QueryBuilder
    {
        return $model->newQuery()->with('indikatorSasarans');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sasaran-kinerja-table')
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
            Column::make('sasaran_kinerja')
                ->title('Sasaran Kinerja'),
            Column::make('keterangan')
                ->title('Keterangan'),
            Column::make('level')
                ->title('Level'),
            Column::computed('jumlah_indikator')
                ->title('Jumlah Indikator')
                ->addClass('text-center')
                ->searchable(false),
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
        return 'SasaranKinerja_' . date('YmdHis');
    }
}
