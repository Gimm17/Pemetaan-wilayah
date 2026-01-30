<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Services\TinggedeExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationAjaxController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');

        $bbox = $request->query('bbox'); // "minLng,minLat,maxLng,maxLat"

        $query = Location::query()
            ->select(['id','nama','nop','category_id','latitude','longitude'])
            ->when($categoryId, fn ($qq) => $qq->where('category_id', $categoryId));

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nop', 'like', "%{$q}%");
            });
        }

        if ($bbox) {
            $parts = array_map('trim', explode(',', (string) $bbox));
            if (count($parts) === 4) {
                [$minLng, $minLat, $maxLng, $maxLat] = $parts;
                $query->whereBetween('latitude', [(float) $minLat, (float) $maxLat])
                      ->whereBetween('longitude', [(float) $minLng, (float) $maxLng]);
            }
        }

        $items = $query->limit(5000)->get();

        return response()->json(['data' => $items]);
    }

    public function checkExact(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if ($lat === null || $lng === null) {
            return response()->json(['message' => 'lat and lng are required'], 422);
        }

        $latN = $this->normalizeCoord($lat);
        $lngN = $this->normalizeCoord($lng);

        $loc = Location::query()
            ->select(['id','nama','nop','latitude','longitude','category_id'])
            ->where('latitude', $latN)
            ->where('longitude', $lngN)
            ->first();

        if (!$loc) {
            return response()->json(['found' => false]);
        }

        return response()->json(['found' => true, 'data' => $loc]);
    }

    public function nopAvailable(Request $request)
    {
        $nop = trim((string) $request->query('nop', ''));
        $excludeId = $request->query('exclude_id');

        if ($nop === '') {
            return response()->json(['available' => true]);
        }

        $query = Location::query()->where('nop', $nop);
        if ($excludeId) {
            $query->where('id', '!=', (int) $excludeId);
        }

        return response()->json(['available' => !$query->exists()]);
    }

    public function bulkCheck(Request $request, TinggedeExcelService $excel)
    {
        $request->validate([
            'file' => ['required','file','max:20480'],
        ]);

        $path = $request->file('file')->store('tmp/bulk_check', 'local');

        $parsed = $excel->parse(Storage::disk('local')->path($path));
        $rows = $parsed['rows'] ?? [];

        if (count($rows) > 5000) {
            Storage::disk('local')->delete($path);
            return response()->json(['message' => 'Terlalu banyak baris (maks 5000)'], 422);
        }

        $results = [];
        foreach ($rows as $i => $row) {
            $lat = $row['LATITUDE'] ?? $row['latitude'] ?? null;
            $lng = $row['LONGTITUDE'] ?? $row['LONGITUDE'] ?? $row['longitude'] ?? null;

            if ($lat === null || $lng === null || trim((string) $lat) === '' || trim((string) $lng) === '') {
                $results[] = ['row' => $i + 1, 'found' => false, 'reason' => 'LAT/LNG kosong'];
                continue;
            }

            $latN = $this->normalizeCoord($lat);
            $lngN = $this->normalizeCoord($lng);

            $loc = Location::query()
                ->select(['id','nama','nop','latitude','longitude','category_id'])
                ->where('latitude', $latN)
                ->where('longitude', $lngN)
                ->first();

            if (!$loc) {
                $results[] = ['row' => $i + 1, 'found' => false, 'latitude' => $latN, 'longitude' => $lngN];
                continue;
            }

            $results[] = ['row' => $i + 1, 'found' => true, 'data' => $loc];
        }

        Storage::disk('local')->delete($path);

        return response()->json(['count' => count($results), 'data' => $results]);
    }

    private function normalizeCoord($value): string
    {
        $f = (float) str_replace(',', '.', (string) $value);
        return number_format($f, 6, '.', '');
    }
}
