<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LocationsImport;

class LocationImportController extends Controller
{
    public function create()
    {
        return view('locations.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'], // max 20MB
        ]);

        try {
            Excel::import(new LocationsImport, $request->file('file'));

            return redirect()
                ->route('locations.import')
                ->with('success', 'Import berhasil ✅');
        } catch (\Throwable $e) {
            return back()->with('error', 'Import gagal: '.$e->getMessage());
        }
    }
}
