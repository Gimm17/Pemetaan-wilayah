<?php

namespace App\Livewire\Imports;

use App\Models\Location;
use App\Services\TinggedeExcelService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

class ImportWizard extends Component
{
    use WithFileUploads;

    public $file;
    public $preview = [];           // All rows from Excel
    public $duplicates = [];        // Array of [rowIdx => existingLocationData]
    public $invalidRows = [];       // Array of [rowIdx => reason_string]
    public $userChoices = [];       // Array of [rowIdx => 'update'|'keep'|'skip']
    public $errorsReport = [];
    public $ready = false;
    public $stats = [
        'total' => 0,
        'new' => 0,
        'duplicate' => 0,
        'invalid' => 0,
    ];

    public $importFinished = false;
    public $finalStats = [];
    
    public $kodeDesa = '';           // User input kode desa (override detected)
    public $detectedKodeDesa = null; // Kode desa detected from Excel header
    public $perPage = 10;            // Default limit preview data (10, 25, 50, 100, 250, 'all')

    public function updatedFile()
    {
        $this->importFinished = false;
        $this->finalStats = [];
        $this->preview = [];
        $this->duplicates = [];
        $this->invalidRows = [];
        $this->userChoices = [];
        $this->errorsReport = [];
        $this->ready = false;
        $this->stats = ['total' => 0, 'new' => 0, 'duplicate' => 0, 'invalid' => 0];
        $this->kodeDesa = '';
        $this->detectedKodeDesa = null;
        $this->previewFile();
    }

    public function previewFile()
    {
        $this->validate(['file' => ['required','file','max:20480']]);

        try {
            $path = $this->file->store('tmp/import_preview', 'local');
            $svc = app(TinggedeExcelService::class);
            $parsed = $svc->parse(Storage::disk('local')->path($path));
            Storage::disk('local')->delete($path);

            $rowsRaw = $parsed['rows'] ?? [];
            
            // FILTER & NORMALIZE ROWS FOR PREVIEW
            $cleanedRows = [];
            $invalidCount = 0;

            foreach ($rowsRaw as $raw) {
                // Normalize keys to uppercase to handle varied casings (e.g., 'Nop', 'nop', 'NOP')
                $r = array_change_key_case($raw, CASE_UPPER);
                
                $nama = trim((string)($r['NAMA'] ?? ''));
                $nop  = trim((string)($r['NOP'] ?? ''));
                
                // Fix typo possibility for Longitude
                $lngKey = array_key_exists('LONGTITUDE', $r) ? 'LONGTITUDE' : 'LONGITUDE';
                
                $lat = trim((string)($r['LATITUDE'] ?? ''));
                $lng = trim((string)($r[$lngKey] ?? ''));

                $isValid = true;
                $reason = '';

                // Filter 1: Must have NAMA or NOP
                if (($nama === '' && $nop === '')) {
                    $isValid = false;
                    $reason = 'Tidak ditemukan NAMA/NOP (Data tidak diinput)';
                }

                // Filter 2: Must have LAT and LNG (if strictly required)
                // Note: user requested invalid data to be SHOWN.
                if ($isValid && ($lat === '' || $lng === '')) {
                     $isValid = false;
                     $reason = 'Tidak ditemukan Latitude/Longitude (Data tidak diinput)';
                }
                
                // Add to preview regardless, but mark if invalid
                if (!$isValid) {
                    $this->invalidRows[count($cleanedRows)] = $reason;
                    $invalidCount++;
                }

                $cleanedRows[] = $r;
            }

            $rows = $cleanedRows;
            $this->preview = $rows;
            $this->stats['total'] = count($rows);
            $this->stats['invalid'] = $invalidCount;
            
            // Capture kode_desa from Excel header if detected
            $this->detectedKodeDesa = $parsed['kode_desa'] ?? null;

            // Check for duplicates (only by NOP, FID no longer used)
            // OPTIMIZED: Batch fetch all existing NOPs in ONE query instead of N+1
            $this->duplicates = [];
            $this->userChoices = [];
            $dupCount = 0;

            // Step 1: Collect all NOPs from valid rows
            $allNops = collect($rows)
                ->filter(fn($r, $idx) => !isset($this->invalidRows[$idx]))
                ->map(fn($r) => trim((string) ($r['NOP'] ?? '')))
                ->filter(fn($nop) => $nop !== '')
                ->unique()
                ->values()
                ->all();

            // Step 2: Batch fetch existing locations by NOP (single query)
            $existingLocations = count($allNops) > 0 
                ? Location::whereIn('nop', $allNops)->get()->keyBy('nop')
                : collect();

            // Step 3: Mark duplicates using the fetched data
            foreach ($rows as $idx => $r) {
                if (isset($this->invalidRows[$idx])) {
                    continue;
                }

                $nop = trim((string) ($r['NOP'] ?? ''));

                if ($nop !== '' && $existingLocations->has($nop)) {
                    $this->duplicates[$idx] = $existingLocations->get($nop)->toArray();
                    $this->userChoices[$idx] = 'update'; // default: update dengan Excel
                    $dupCount++;
                }
            }

            $this->stats['duplicate'] = $dupCount;
            $this->stats['new'] = $this->stats['total'] - $dupCount - $invalidCount;
            $this->ready = true;

            if ($this->stats['total'] === 0) {
                 $this->dispatch('swal:toast', type: 'warning', message: "Tidak ada data valid ditemukan (Cek NOP/NAMA & Latitude/Longitude).");
            } else {
                 $this->dispatch('swal:toast', type: 'success', message: "Preview siap. {$this->stats['new']} data baru, {$dupCount} duplikat.");
            }

        } catch (\Exception $e) {
            $this->dispatch('swal:toast', type: 'error', message: "Gagal preview file: " . $e->getMessage());
            $this->resetImport();
        }
    }

    /**
     * Set user choice for a specific row
     */
    public function setChoice($rowIdx, $choice)
    {
        if (isset($this->duplicates[$rowIdx])) {
            $this->userChoices[$rowIdx] = $choice; // 'update', 'keep', or 'skip'
        }
    }

    // --- Confirmation Methods ---

    public function confirmCommit()
    {
        $this->dispatch('swal:confirm', 
            title: 'Konfirmasi Import', 
            text: 'Apakah Anda yakin ingin menyimpan data ini ke database?', 
            icon: 'warning', 
            method: 'commit-process'
        );
    }

    public function confirmUpdateAll()
    {
        $this->dispatch('swal:confirm', 
            title: 'Update Semua Duplikat', 
            text: 'Semua data yang duplikat akan di-OVERWRITE dengan data dari Excel. Lanjutkan?', 
            icon: 'warning', 
            method: 'updateAllDuplicates'
        );
    }

    public function confirmSkipAll()
    {
        $this->dispatch('swal:confirm', 
            title: 'Skip Semua Duplikat', 
            text: 'Semua data yang duplikat akan DIABAIKAN (tidak di-import). Lanjutkan?', 
            icon: 'info', 
            method: 'skipAllDuplicates'
        );
    }

    /**
     * Bulk action: skip all duplicates
     */
    #[On('skipAllDuplicates')]
    public function skipAllDuplicates()
    {
        foreach ($this->duplicates as $idx => $existing) {
            $this->userChoices[$idx] = 'skip';
        }
        $this->dispatch('swal:toast', type: 'info', message: 'Semua duplikat akan di-skip.');
    }

    /**
     * Bulk action: update all duplicates with Excel data
     */
    #[On('updateAllDuplicates')]
    public function updateAllDuplicates()
    {
        foreach ($this->duplicates as $idx => $existing) {
            $this->userChoices[$idx] = 'update';
        }
        $this->dispatch('swal:toast', type: 'info', message: 'Semua duplikat akan di-update dengan data Excel.');
    }

    /**
     * Bulk action: keep all existing data (don't update)
     */
    public function keepAllDuplicates()
    {
        foreach ($this->duplicates as $idx => $existing) {
            $this->userChoices[$idx] = 'keep';
        }
        $this->dispatch('swal:toast', type: 'info', message: 'Semua duplikat akan mempertahankan data lama.');
    }

    #[On('commit-process')]
    public function commit()
    {
        if (!$this->file) {
             $this->dispatch('swal:toast', type: 'error', message: 'File tidak ditemukan atau sesi habis. Silakan upload ulang.');
             return;
        }

        $this->validate(['file' => ['required','file','max:20480']]);

        try {
            $path = $this->file->store('tmp/import_commit', 'local');
            $svc = app(TinggedeExcelService::class);
            $parsed = $svc->parse(Storage::disk('local')->path($path));
            Storage::disk('local')->delete($path);

            $rowsRaw = $parsed['rows'] ?? [];
            $errors = [];
            $inserted = 0;
            $updated = 0;
            $skipped = 0;

            $processedNops = []; // Track NOPs processed in this import batch

            // OPTIMIZED: Pre-fetch all existing locations by NOP before processing
            // Step 1: Collect all NOPs from rowsRaw
            $allNops = collect($rowsRaw)
                ->map(function ($raw) {
                    $r = array_change_key_case($raw, CASE_UPPER);
                    return trim((string) ($r['NOP'] ?? ''));
                })
                ->filter(fn($nop) => $nop !== '')
                ->unique()
                ->values()
                ->all();

            // Step 2: Batch fetch all existing locations (single query)
            $existingByNop = count($allNops) > 0
                ? Location::whereIn('nop', $allNops)->get()->keyBy('nop')
                : collect();

            DB::transaction(function () use ($rowsRaw, &$errors, &$inserted, &$updated, &$skipped, &$processedNops, $existingByNop) {
                foreach ($rowsRaw as $idx => $raw) {
                    // Normalize keys
                    $r = array_change_key_case($raw, CASE_UPPER);
                    
                    $lat = $r['LATITUDE'] ?? null;
                    $lngKey = array_key_exists('LONGTITUDE', $r) ? 'LONGTITUDE' : 'LONGITUDE';
                    $lng = $r[$lngKey] ?? null;

                    if ($lat === null || $lng === null || trim((string)$lat)==='' || trim((string)$lng)==='') {
                        $errors[] = ['row' => $idx+1, 'reason' => 'Tidak ditemukan Latitude/Longitude (Data tidak diinput)'];
                        $skipped++;
                        continue;
                    }

                    $latN = number_format((float) str_replace(',', '.', (string) $lat), 6, '.', '');
                    $lngN = number_format((float) str_replace(',', '.', (string) $lng), 6, '.', '');

                    if ((float)$latN < -90 || (float)$latN > 90 || (float)$lngN < -180 || (float)$lngN > 180) {
                        $errors[] = ['row' => $idx+1, 'reason' => 'Latitude/Longitude tidak valid (Data tidak diinput)'];
                        $skipped++;
                        continue;
                    }

                    $nop = isset($r['NOP']) ? trim((string) $r['NOP']) : null;
                    $nama = isset($r['NAMA']) ? trim((string) $r['NAMA']) : null;
                    
                    // Skip rows without NAMA and NOP (at least one must be present)
                    if (($nop === null || $nop === '') && ($nama === null || $nama === '')) {
                        $errors[] = ['row' => $idx+1, 'reason' => 'Tidak ditemukan NAMA/NOP (Data tidak diinput)'];
                        $skipped++;
                        continue;
                    }
                    
                    // CHECK INTERNAL DUPLICATE (Duplicate NOP inside the same Excel file)
                    if ($nop !== null && $nop !== '' && isset($processedNops[$nop])) {
                        $errors[] = ['row' => $idx+1, 'reason' => "NOP {$nop} duplikat di dalam file ini (baris sebelumnya sudah diproses)"];
                        $skipped++;
                        continue;
                    }
                    
                    // Determine final kode_desa: user input takes priority over detected
                    $finalKodeDesa = trim($this->kodeDesa) !== '' ? trim($this->kodeDesa) : $this->detectedKodeDesa;

                    // Check if this row is a duplicate in DB (using pre-fetched data)
                    $isDuplicate = isset($this->duplicates[$idx]);
                    $userChoice = $this->userChoices[$idx] ?? 'update';

                    if ($isDuplicate) {
                        if ($userChoice === 'skip') {
                            $skipped++;
                            continue;
                        }

                        if ($userChoice === 'keep') {
                            $skipped++;
                            continue;
                        }

                        // userChoice === 'update' - use pre-fetched existing location
                        $existing = ($nop !== null && $nop !== '') ? $existingByNop->get($nop) : null;

                        if ($existing) {
                            $existing->update([
                                'kode_desa' => $finalKodeDesa ?? $existing->kode_desa,
                                'shape' => $r['Shape'] ?? ($r['SHAPE'] ?? $existing->shape),
                                'nama' => $r['NAMA'] ?? $existing->nama,
                                'nop' => ($nop === '') ? $existing->nop : $nop,
                                'luas' => $this->cleanDecimal($r['LUAS'] ?? $existing->luas),
                                'sertpikat' => isset($r['SERTPIKAT']) ? ($r['SERTPIKAT'] ?: '-') : $existing->sertpikat,
                                'njop' => $this->cleanDecimal($r['NJOP'] ?? $existing->njop),
                                'luas_bangu' => $this->cleanDecimal($r['LUAS_BANGU'] ?? $existing->luas_bangu),
                                'user_perum' => isset($r['USER_PERUM']) ? ($r['USER_PERUM'] ?: '-') : $existing->user_perum,
                                'latitude' => $latN,
                                'longitude' => $lngN,
                                'updated_by' => auth()->id(),
                            ]);
                            $updated++;
                            // Mark processed
                            if ($nop) $processedNops[$nop] = true;
                            continue;
                        }
                    }

                    // Confirm NOP uniqueness using pre-fetched data (no extra query)
                    if ($nop !== null && $nop !== '') {
                        if ($existingByNop->has($nop) && !isset($processedNops[$nop])) {
                            // NOP exists in DB but wasn't marked as duplicate - race condition safety
                            $errors[] = ['row' => $idx+1, 'reason' => "NOP {$nop} sudah ada di database"];
                            $skipped++;
                            continue;
                        }
                    }

                    // New record - insert
                    Location::create([
                        'kode_desa' => $finalKodeDesa ?: '-',
                        'shape' => $r['Shape'] ?? ($r['SHAPE'] ?? 'Point'),
                        'nama' => $r['NAMA'] ?? null,
                        'nop' => ($nop === '') ? null : $nop,
                        'luas' => $this->cleanDecimal($r['LUAS'] ?? 0),
                        'sertpikat' => $r['SERTPIKAT'] ?? '-',
                        'njop' => $this->cleanDecimal($r['NJOP'] ?? 0),
                        'luas_bangu' => $this->cleanDecimal($r['LUAS_BANGU'] ?? 0),
                        'user_perum' => $r['USER_PERUM'] ?? '-',
                        'latitude' => $latN,
                        'longitude' => $lngN,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                    $inserted++;
                    // Mark processed
                    if ($nop) $processedNops[$nop] = true;
                }
            });

            $this->errorsReport = $errors;
            
            $this->finalStats = [
                'inserted' => $inserted,
                'updated' => $updated,
                'skipped' => $skipped,
            ];
            
            $this->dispatch('swal:toast', type: ($inserted || $updated) ? 'success' : 'warning', message: "Import selesai. Insert: {$inserted}, Update: {$updated}, Skip: {$skipped}");

            if ($inserted > 0 || $updated > 0) {
                 $this->importFinished = true;
            }

        } catch (\Exception $e) {
            $this->dispatch('swal:toast', type: 'error', message: 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    private function cleanDecimal($val)
    {
        if (is_null($val) || trim((string)$val) === '') {
            return 0; // Or null if your DB allows it, assuming 0 for safety based on casts
        }
        $val = str_replace(',', '.', (string)$val);
        // Remove non-numeric characters except dot and minus
        $val = preg_replace('/[^0-9.\-]/', '', $val);
        return (float) $val;
    }

    public function resetImport()
    {
        $this->file = null;
        $this->preview = [];
        $this->duplicates = [];
        $this->userChoices = [];
        $this->errorsReport = [];
        $this->ready = false;
        $this->importFinished = false;
        $this->finalStats = [];
        $this->stats = ['total' => 0, 'new' => 0, 'duplicate' => 0];
        $this->kodeDesa = '';
        $this->detectedKodeDesa = null;
    }

    public function render()
    {
        return view('livewire.imports.import-wizard');
    }
}
