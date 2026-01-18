<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;

class UsersController extends Controller
{
    public function index()
    {
        return view('pages.users.index');
    }
}
