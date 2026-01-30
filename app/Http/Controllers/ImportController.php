<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        return view('import.index');
    }

    public function preview(Request $request) { abort(404); }
    public function commit(Request $request) { abort(404); }
}
