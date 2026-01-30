<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;

class TinggedeExcelService
{
    public function parse(string $path): array
    {
        $sheets = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
            public function array(array $array) { return $array; }
        }, $path);
        $rows = $sheets[0] ?? [];
        if (count($rows) === 0) return ['header' => [], 'rows' => [], 'kode_desa' => null];

        // Try to extract kode_desa from cell A1 (format: "72.10.140.010 KODE DESA")
        $kodeDesa = null;
        $firstCell = isset($rows[0][0]) ? trim((string) $rows[0][0]) : '';
        if ($firstCell !== '' && preg_match('/^([\d\.]+)\s*KODE\s*DESA/i', $firstCell, $matches)) {
            $kodeDesa = $matches[1];
        }

        $headerIndex = 0;
        for ($i = 0; $i < min(10, count($rows)); $i++) {
            $r = array_map(fn ($x) => is_string($x) ? trim($x) : $x, $rows[$i] ?? []);
            $joined = strtoupper(implode(' ', array_map('strval', $r)));
            if (str_contains($joined, 'LATITUDE') && (str_contains($joined, 'LONGTITUDE') || str_contains($joined, 'LONGITUDE'))) {
                $headerIndex = $i;
                break;
            }
        }

        $headerRow = $rows[$headerIndex] ?? [];
        $header = array_map(function ($h) {
            $h = is_string($h) ? trim($h) : (string) $h;
            $h = preg_replace('/\s*\*\s*$/', '', $h);
            return $h;
        }, $headerRow);

        // Find FID column index to skip it
        $fidColumnIndex = null;
        foreach ($header as $idx => $key) {
            if (strtoupper($key) === 'FID') {
                $fidColumnIndex = $idx;
                break;
            }
        }

        $dataRows = array_slice($rows, $headerIndex + 1);

        $assoc = [];
        foreach ($dataRows as $r) {
            if (!is_array($r)) continue;
            $rowAssoc = [];
            foreach ($header as $idx => $key) {
                if ($key === '') continue;
                // Skip FID column
                if ($fidColumnIndex !== null && $idx === $fidColumnIndex) continue;
                $rowAssoc[$key] = $r[$idx] ?? null;
            }
            $nonEmpty = array_filter($rowAssoc, fn ($v) => $v !== null && trim((string) $v) !== '');
            if (count($nonEmpty) === 0) continue;
            $assoc[] = $rowAssoc;
        }

        // Remove FID from header array as well
        $headerWithoutFid = array_values(array_filter($header, fn ($key) => strtoupper($key) !== 'FID'));

        return ['header' => $headerWithoutFid, 'rows' => $assoc, 'kode_desa' => $kodeDesa];
    }
}
