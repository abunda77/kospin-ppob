<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<Role>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($role) {
                return view('pages.roles.action', compact('role'));
            })
            ->editColumn('permissions_count', function ($role) {
                return $role->permissions_count;
            })
            ->editColumn('users_count', function ($role) {
                return $role->users_count;
            })
            ->editColumn('created_at', function ($role) {
                return $role->created_at->format('d M Y H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()
            ->withCount(['permissions', 'users'])
            ->select('roles.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel'),
                Button::make('csv')->text('<i class="bi bi-file-earmark-csv"></i> CSV'),
                Button::make('print')->text('<i class="bi bi-printer"></i> Print'),
                Button::make('reset')->text('<i class="bi bi-arrow-clockwise"></i> Reset'),
                Button::make('reload')->text('<i class="bi bi-arrow-repeat"></i> Reload'),
            ])
            ->parameters([
                'dom' => 'Bfrtip',
                'responsive' => true,
                'autoWidth' => false,
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::make('name')->title('Role Name'),
            Column::make('permissions_count')->title('Permissions')->orderable(false)->searchable(false),
            Column::make('users_count')->title('Users')->orderable(false)->searchable(false),
            Column::make('created_at')->title('Created At'),
            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Roles_'.date('YmdHis');
    }
}
