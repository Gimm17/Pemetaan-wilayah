<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NominatimService
{
    public function reverse(string $lat, string $lng): ?string
    {
        try {
            $res = Http::withHeaders([
                'User-Agent' => config('app.name', 'PaluGIS') . '/1.0',
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'jsonv2',
                'lat' => $lat,
                'lon' => $lng,
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

            if (!$res->ok()) return null;
            $j = $res->json();
            return $j['display_name'] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
