<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LogController extends Controller
{
    public function index(): View
    {
        return view('log.index');
    }
}
