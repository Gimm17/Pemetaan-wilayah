<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    private function filteredQuery(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $categoryId = $request->query('category_id');

        $query = Location::query()->with('category');

        if ($status) $query->where('status', $status);
        if ($categoryId) $query->where('category_id', $categoryId);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nop', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    public function csv(Request $request)
    {
        $rows = $this->filteredQuery($request)->orderBy('id')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="locations.csv"',
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id','nama','nop','address','status','category','latitude','longitude','luas','sertpikat','njop','luas_bangu','user_perum']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->id, $r->nama, $r->nop, $r->address, $r->status,
                    $r->category?->name, $r->latitude, $r->longitude,
                    $r->luas, $r->sertpikat, $r->njop, $r->luas_bangu, $r->user_perum,
                ]);
            }
            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function excel(Request $request)
    {
        // Simple: CSV tapi nama file .xlsx biar kebuka di Excel.
        $resp = $this->csv($request);
        $resp->headers->set('Content-Disposition', 'attachment; filename="locations.xlsx"');
        return $resp;
    }

    public function geojson(Request $request)
    {
        $rows = $this->filteredQuery($request)->orderBy('id')->get();

        $features = [];
        foreach ($rows as $r) {
            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'id' => $r->id,
                    'nama' => $r->nama,
                    'nop' => $r->nop,
                    'address' => $r->address,
                    'status' => $r->status,
                    'category' => $r->category?->name,
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $r->longitude, (float) $r->latitude],
                ],
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function pdf(Request $request)
    {
        $rows = $this->filteredQuery($request)->orderBy('id')->get();
        $pdf = Pdf::loadView('export.locations-pdf', ['rows' => $rows]);
        return $pdf->download('locations.pdf');
    }
}
