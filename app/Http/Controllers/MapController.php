<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LocationsImport;

class MapController extends Controller
{
    public function index()
    {
        // view map kamu: resources/views/map/index.blade.php
        return view('map.index');
    }

    public function ajaxLocations(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        // Guard: return empty array if no search criteria provided
        // This prevents loading all markers on initial map load
        if ($q === '' && $lat === null && $lng === null) {
            return response()->json([
                'data' => [],
            ]);
        }

        $query = Location::query()
            ->select([
                'id', 'kode_desa', 'shape', 'nama', 'nop', 'luas', 'sertpikat', 'njop',
                'luas_bangu', 'user_perum', 'latitude', 'longitude'
            ]);

        // Filter by nama/nop
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nop', 'like', "%{$q}%")
                  ->orWhere('nama', 'like', "%{$q}%");
            });
        }

        // Filter by latitude (exact match with small tolerance)
        if ($lat !== null && trim((string) $lat) !== '') {
            $latFloat = (float) str_replace(',', '.', (string) $lat);
            $eps = 0.000001;
            $query->whereBetween('latitude', [$latFloat - $eps, $latFloat + $eps]);
        }

        // Filter by longitude (exact match with small tolerance)
        if ($lng !== null && trim((string) $lng) !== '') {
            $lngFloat = (float) str_replace(',', '.', (string) $lng);
            $eps = 0.000001;
            $query->whereBetween('longitude', [$lngFloat - $eps, $lngFloat + $eps]);
        }

        $data = $query
            ->orderBy('nama')
            ->limit(50)
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function ajaxCheckExact(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if ($lat === null || $lng === null) {
            return response()->json(['found' => false, 'message' => 'lat/lng kosong'], 400);
        }

        $lat = $this->toFloat($lat);
        $lng = $this->toFloat($lng);

        if ($lat === null || $lng === null) {
            return response()->json(['found' => false, 'message' => 'lat/lng tidak valid'], 400);
        }

        // “exact match” tapi toleransi kecil biar tidak gagal karena beda 0.0000001
        $eps = 0.000001;

        $row = Location::query()
            ->whereBetween('latitude', [$lat - $eps, $lat + $eps])
            ->whereBetween('longitude', [$lng - $eps, $lng + $eps])
            ->first();

        if (!$row) {
            return response()->json([
                'found' => false,
                'message' => 'Tidak ada',
            ]);
        }

        return response()->json([
            'found' => true,
            'data' => $row,
        ]);
    }

    public function importForm()
    {
        // view import: resources/views/locations/import.blade.php
        return view('locations.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $import = new LocationsImport(auth()->id());

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('locations.import')
            ->with('success', "Import selesai. Inserted: {$import->inserted}, Updated: {$import->updated}, Skipped: {$import->skipped}");
    }

    /**
     * GET /locations/create
     * Form untuk tambah lokasi manual (1 data)
     */
    public function createLocation()
    {
        return view('locations.create');
    }

    /**
     * POST /locations
     * Simpan lokasi manual
     */
    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'nama'       => ['nullable', 'string', 'max:255'],
            'nop'        => ['nullable', 'string', 'max:100', 'unique:locations,nop'],
            'kode_desa'  => ['nullable', 'string', 'max:50'],
            'shape'      => ['nullable', 'string', 'max:100'],
            'luas'       => ['nullable', 'numeric', 'min:0'],
            'sertpikat'  => ['nullable', 'string', 'max:255'],
            'njop'       => ['nullable', 'numeric', 'min:0'],
            'luas_bangu' => ['nullable', 'numeric', 'min:0'],
            'user_perum' => ['nullable', 'string', 'max:255'],
            'latitude'   => ['required', 'numeric', 'between:-90,90'],
            'longitude'  => ['required', 'numeric', 'between:-180,180'],
        ], [
            'nop.unique' => 'NOP sudah terdaftar di database! Silakan gunakan NOP yang berbeda atau edit data yang sudah ada.',
        ]);

        // Set default values untuk field yang NOT NULL di database
        $validated['shape']      = $validated['shape'] ?? 'Point';
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Location::create($validated);

        return redirect()
            ->route('locations.create')
            ->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * GET /locations/{id}/edit
     * Form edit lokasi
     */
    public function editLocation(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        
        // Use 'source' param to determine return destination safely
        $source = $request->query('source', 'locations');
        $validSources = ['map', 'locations'];
        if (!in_array($source, $validSources)) {
            $source = 'locations';
        }
        
        return view('locations.edit', compact('location', 'source'));
    }

    public function updateLocation(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $validated = $request->validate([
            'nama'       => ['nullable', 'string', 'max:255'],
            'nop'        => ['nullable', 'string', 'max:100', 'unique:locations,nop,' . $id],
            'kode_desa'  => ['nullable', 'string', 'max:50'],
            'shape'      => ['nullable', 'string', 'max:100'],
            'luas'       => ['nullable', 'numeric', 'min:0'],
            'sertpikat'  => ['nullable', 'string', 'max:255'],
            'njop'       => ['nullable', 'numeric', 'min:0'],
            'luas_bangu' => ['nullable', 'numeric', 'min:0'],
            'user_perum' => ['nullable', 'string', 'max:255'],
            'latitude'   => ['required', 'numeric', 'between:-90,90'],
            'longitude'  => ['required', 'numeric', 'between:-180,180'],
        ], [
            'nop.unique' => 'NOP sudah digunakan oleh lokasi lain.',
        ]);

        $validated['updated_by'] = auth()->id();

        $location->update($validated);

        // Determine redirect based on safe source param
        $source = $request->input('source', 'locations');
        $redirectRoute = ($source === 'map') ? 'map' : 'locations.index';
        
        return redirect()->route($redirectRoute)
            ->with('success', 'Lokasi berhasil diperbarui!');
    }

    /**
     * DELETE /ajax/locations/{id}
     * Hapus lokasi via AJAX
     */
    public function ajaxDeleteLocation($id)
    {
        // Debug: check global scopes too
        $location = Location::withoutGlobalScopes()->find($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan (ID Received: ' . var_export($id, true) . ')',
            ], 404);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil dihapus.',
        ]);
    }

    private function toFloat($value): ?float
    {
        if ($value === null) return null;
        $s = trim((string) $value);
        if ($s === '') return null;

        // dukung koma: -0,9341
        $s = str_replace(',', '.', $s);

        if (!is_numeric($s)) return null;
        return (float) $s;
    }
}
