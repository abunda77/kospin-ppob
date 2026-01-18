<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;

class RolesController extends Controller
{
    public function index()
    {
        return view('pages.roles.index');
    }
}
