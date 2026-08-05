<?php

namespace App\DataTables;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($item) {
                return '<div class="form-check d-flex justify-content-center align-items-center"><input class="form-check-input row-checkbox" type="checkbox" value="' . $item->id . '"></div>';
            })
            ->addColumn('nama_staff', function ($item) {
                return '<span class="fw-semibold text-dark">' . e($item->nama_staff) . '</span>';
            })
            ->addColumn('nup', function ($item) {
                return e($item->nup ?? '-');
            })
            ->addColumn('unit_kerja', function ($item) {
                return $item->unitKerja ? e($item->unitKerja->nama_unit_kerja) : '-';
            })
            ->filterColumn('unit_kerja', function ($query, $keyword) {
                $query->whereHas('unitKerja', function ($q) use ($keyword) {
                    $q->where('nama_unit_kerja', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('unit_kerja', function ($query, $order) {
                $query->leftJoin('unit_kerjas', 'staff.unit_kerja_id', '=', 'unit_kerjas.id')
                    ->orderBy('unit_kerjas.nama_unit_kerja', $order)
                    ->select('staff.*');
            })
            ->addColumn('jabatan', function ($item) {
                return e($item->jabatan ?? '-');
            })
            ->addColumn('email', function ($item) {
                return e($item->email ?? '-');
            })
            ->addColumn('nomor_hp', function ($item) {
                return e($item->nomor_hp ?? '-');
            })
            ->addColumn('status', function ($item) {
                if ($item->status === 'Aktif') {
                    return '<span class="badge bg-success-light text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>';
                }
                return '<span class="badge bg-secondary-light text-secondary border border-secondary-subtle px-2 py-1">' . e($item->status ?? 'Tidak Aktif') . '</span>';
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                
                // Edit
                $btn .= '<a href="' . route('staff.edit', $item->id) . '" class="btn btn-sm btn-outline-primary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Edit"><i class="bi bi-pencil" style="font-size: 13px;"></i></a>';
                
                // Delete
                $btn .= '<form action="' . route('staff.destroy', $item->id) . '" method="POST" class="m-0">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-outline-danger btn-sm rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus staff ini?\')"><i class="bi bi-trash" style="font-size: 13px;"></i></button></form>';
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['checkbox', 'nama_staff', 'status', 'action']);
    }

    public function query(Staff $model): QueryBuilder
    {
        $query = $model->newQuery()->with('unitKerja')->select('staff.*')->orderBy('staff.created_at', 'desc');

        if ($unitKerjaId = request('unit_kerja_id')) {
            $query->where('unit_kerja_id', $unitKerjaId);
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('staff-table')
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
            Column::make('nama_staff')->title('Nama Staff'),
            Column::make('nup')->title('NUP'),
            Column::make('unit_kerja')->title('Unit Kerja'),
            Column::make('jabatan')->title('Jabatan'),
            Column::make('email')->title('Email'),
            Column::make('nomor_hp')->title('Nomor Telepon'),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width('10%')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Staff_' . date('YmdHis');
    }
}
