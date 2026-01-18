<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($user) {
                return view('pages.users.action', compact('user'));
            })
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->join(', ');
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d M Y H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->with('roles')->select('users.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
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
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('roles')->title('Roles')->orderable(false)->searchable(false),
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
        return 'Users_'.date('YmdHis');
    }
}
