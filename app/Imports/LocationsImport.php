<?php

namespace App\Imports;

use App\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LocationsImport implements ToCollection, WithHeadingRow, WithStartRow
{
    public int $inserted = 0;
    public int $updated  = 0;
    public int $skipped  = 0;

    public function __construct(private int $userId)
    {
    }

    // Header ada di row 2
    public function headingRow(): int
    {
        return 2;
    }

    // Data mulai row 3
    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalisasi key jadi lowercase
            $r = [];
            foreach ($row->toArray() as $k => $v) {
                $r[strtolower(trim((string) $k))] = $v;
            }

            // ambil lat/lng (dukung latitude/longitude, lat/lng, longtitude typo)
            $lat = $this->toFloat($r['latitude'] ?? $r['lat'] ?? null);
            $lng = $this->toFloat($r['longitude'] ?? $r['lng'] ?? $r['longtitude'] ?? null);

            // Kalau tidak ada lat/lng → skip (sesuai tips kamu)
            if ($lat === null || $lng === null) {
                $this->skipped++;
                continue;
            }

            $fid  = $this->nullIfEmpty($r['fid'] ?? null);
            $nop  = $this->nullIfEmpty($r['nop'] ?? null);
            $nama = $this->nullIfEmpty($r['nama'] ?? null);

            $payload = [
                'fid'        => $fid,
                'shape'      => $this->nullIfEmpty($r['shape'] ?? null),
                'nama'       => $nama,
                'nop'        => $nop,
                'luas'       => $this->toFloat($r['luas'] ?? null),
                'sertpikat'  => $this->nullIfEmpty($r['sertpikat'] ?? $r['sertifikat'] ?? null),
                'njop'       => $this->toFloat($r['njop'] ?? null),
                'luas_bangu' => $this->toFloat($r['luas_bangu'] ?? $r['luas_bangun'] ?? null),
                'user_perum' => $this->nullIfEmpty($r['user_perum'] ?? null),
                'latitude'   => $lat,
                'longitude'  => $lng,
                'updated_by' => $this->userId,
            ];

            // created_by hanya saat insert
            // Prioritas updateOrCreate:
            // 1) kalau ada NOP → update berdasarkan NOP
            // 2) kalau NOP kosong tapi FID ada → update berdasarkan FID
            // 3) kalau dua-duanya kosong → insert baru
            if ($nop) {
                $existing = Location::where('nop', $nop)->first();
                if ($existing) {
                    $existing->fill($payload)->save();
                    $this->updated++;
                } else {
                    $payload['created_by'] = $this->userId;
                    Location::create($payload);
                    $this->inserted++;
                }
                continue;
            }

            if ($fid) {
                $existing = Location::where('fid', $fid)->first();
                if ($existing) {
                    $existing->fill($payload)->save();
                    $this->updated++;
                } else {
                    $payload['created_by'] = $this->userId;
                    Location::create($payload);
                    $this->inserted++;
                }
                continue;
            }

            $payload['created_by'] = $this->userId;
            Location::create($payload);
            $this->inserted++;
        }
    }

    private function nullIfEmpty($v): ?string
    {
        if ($v === null) return null;
        $s = trim((string) $v);
        return $s === '' ? null : $s;
    }

    private function toFloat($value): ?float
    {
        if ($value === null) return null;
        $s = trim((string) $value);
        if ($s === '') return null;
        $s = str_replace(',', '.', $s);
        if (!is_numeric($s)) return null;
        return (float) $s;
    }
}
