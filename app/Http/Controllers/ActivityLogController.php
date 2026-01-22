<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        return view('pages.activity-log');
    }
}
