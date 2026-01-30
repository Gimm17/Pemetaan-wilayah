<div class="location-list-page" style="min-height: 100vh;">
    <div class="page-wrap">
    
    {{-- Inline Styles for specific wizard needs --}}
    <style>
        .wizard-step { display: flex; align-items: center; gap: 12px; color: var(--muted); font-weight: 500; font-size: 14px; }
        .wizard-step.active { color: var(--primary); font-weight: 700; }
        .wizard-step.active .step-num { background: var(--primary); color: white; border-color: var(--primary); }
        .wizard-step .step-num { 
            width: 28px; height: 28px; border-radius: 50%; border: 2px solid var(--border); 
            display: flex; align-items: center; justify-content: center; font-size: 12px; 
            transition: all 0.3s; 
        }
        .wizard-line { height: 2px; width: 40px; background: var(--border); margin: 0 12px; }
        
        .upload-zone {
            display: block;
            border: 2px dashed var(--border); border-radius: 12px; background: var(--surface-2);
            padding: 40px; text-align: center; transition: all 0.2s; cursor: pointer;
        }
        .upload-zone:hover { border-color: var(--primary); background: var(--primary-soft); }
        
        .stat-card-sm {
            background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px;
            display: flex; flex-direction: column; gap: 4px; box-shadow: var(--shadow-sm);
        }
        
        /* Responsive Layout Classes */
        .import-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px;
        }
        
        .import-grid {
            display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;
        }
        
        @media (max-width: 1024px) {
            .import-header {
                flex-direction: column;
                align-items: stretch;
            }
            .import-grid {
                grid-template-columns: 1fr;
            }
            /* Adjust stepper for mobile */
            .wizard-step {
                font-size: 12px;
                gap: 8px;
            }
            .wizard-line {
                width: 20px;
                margin: 0 8px;
            }
        }

        /* PREVIEW STEP RESPONSIVE */
        .preview-toolbar {
            background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 16px; 
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;
        }
        .stats-group {
            display: flex; gap: 16px; align-items: center;
        }
        .actions-group {
            display: flex; gap: 10px; align-items: center;
        }
        
        @media (max-width: 768px) {
            .preview-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 20px;
                padding: 12px;
            }
            .stats-group {
                display: grid;
                grid-template-columns: 1fr 1fr;
                width: 100%;
                gap: 12px;
            }
            .stat-card-sm {
                min-width: 0 !important;
                padding: 10px !important;
            }
            .stat-card-sm span:first-child { font-size: 9px !important; }
            .stat-card-sm span:last-child { font-size: 18px !important; }

            .actions-group {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }
            .actions-group div[style*="border-right"] {
                width: 100%;
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                margin-right: 0 !important;
                padding-right: 0 !important;
                border-right: none !important;
                border-bottom: 1px solid var(--border);
                padding-bottom: 12px;
            }
            .actions-group button {
                width: 100%;
                justify-content: center;
                padding: 12px !important;
            }

            /* Table Compact */
            .mobile-compact-table th, 
            .mobile-compact-table td {
                font-size: 11px !important;
                padding: 8px 6px !important;
            }
            .hide-on-mobile-col {
                display: none !important;
            }
            
            /* Kode Desa Wrapper */
            .kode-desa-wrapper {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            .kode-desa-wrapper input {
                width: 100% !important;
            }
        }
    </style>

    {{-- SweetAlert Scripts --}}


    {{-- Page Header & Stepper --}}
    <div class="import-header">
        <div>
            <h1 style="font-size: 22px; font-weight: 600; color: var(--text); margin: 0 0 4px 0; line-height: 1.3;">Import Data Lokasi</h1>
            <p style="font-size: 14px; color: var(--muted); margin: 0;">Upload file Excel (.xlsx, .csv) untuk memproses data massal.</p>
        </div>
        
        {{-- Visual Stepper --}}
        <div style="display: flex; align-items: center; background: var(--surface); padding: 10px 20px; border-radius: 50px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); justify-content: center;">
            <div class="wizard-step {{ !$ready && !$importFinished ? 'active' : '' }}">
                <div class="step-num">1</div> Upload
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step {{ $ready && !$importFinished ? 'active' : '' }}">
                <div class="step-num">2</div> Preview & Validasi
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step {{ $importFinished ? 'active' : '' }}">
                <div class="step-num">3</div> Selesai
            </div>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="wizard-content">

        {{-- STEP 3: SUCCESS --}}
        @if($importFinished)
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 40px; text-align: center; box-shadow: var(--shadow-md);">
                <div style="max-width: 800px; margin: 0 auto;">
                    <div style="width: 80px; height: 80px; background: var(--success-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <svg style="width: 40px; height: 40px; color: var(--success);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Import Berhasil!</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 40px;">Data Anda telah berhasil diproses dan disimpan ke sistem.</p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; text-align: left;">
                        <div class="stat-card-sm" style="border-left: 4px solid var(--success); padding: 20px;">
                            <span style="font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; display: block;">Data Baru</span>
                            <span style="font-size: 28px; font-weight: 700; color: var(--text);">{{ $finalStats['inserted'] ?? 0 }}</span>
                        </div>
                        <div class="stat-card-sm" style="border-left: 4px solid var(--primary); padding: 20px;">
                            <span style="font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; display: block;">Diupdate</span>
                            <span style="font-size: 28px; font-weight: 700; color: var(--text);">{{ $finalStats['updated'] ?? 0 }}</span>
                        </div>
                        <div class="stat-card-sm" style="border-left: 4px solid var(--text-secondary); padding: 20px;">
                            <span style="font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; display: block;">Dilewati</span>
                            <span style="font-size: 28px; font-weight: 700; color: var(--text);">{{ $finalStats['skipped'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: center; gap: 16px;">
                        <button wire:click="resetImport" style="padding: 12px 28px; background: var(--primary); color: white; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; font-size: 14px; box-shadow: var(--shadow-sm); transition: all 0.2s;">
                            Import File Lain
                        </button>
                        <a href="{{ route('locations.index') }}" style="padding: 12px 28px; background: var(--surface); color: var(--text); border: 1px solid var(--border); border-radius: 10px; font-weight: 600; text-decoration: none; font-size: 14px; transition: all 0.2s;">
                            Lihat Data Lokasi
                        </a>
                    </div>
                </div>
            </div>

        {{-- STEP 2: PREVIEW --}}
        @elseif($ready)
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                {{-- Top Control Bar --}}
                {{-- Top Control Bar --}}
                <div class="preview-toolbar">
                    
                    {{-- Stats --}}
                    <div class="stats-group">
                        <div class="stat-card-sm">
                            <span style="color: var(--muted); font-size: 10px;">TOTAL BARIS</span>
                            <span style="color: var(--text); font-weight: 700; font-size: 16px;">{{ $stats['total'] }}</span>
                        </div>
                        <div class="stat-card-sm">
                            <span style="color: var(--success); font-size: 10px;">BARU</span>
                            <span style="color: var(--text); font-weight: 700; font-size: 16px;">{{ $stats['new'] }}</span>
                        </div>
                        @if($stats['duplicate'] > 0)
                            <div class="stat-card-sm" style="border-color: var(--warning-soft); background: var(--warning-soft);">
                                <span style="color: var(--warning); font-size: 10px; font-weight: 800;">DUPLIKAT</span>
                                <span style="color: var(--text); font-weight: 700; font-size: 16px;">{{ $stats['duplicate'] }}</span>
                            </div>
                        @endif
                        @if(count($invalidRows) > 0)
                            <div class="stat-card-sm" style="border-color: var(--danger-soft); background: var(--danger-soft);">
                                <span style="color: var(--danger); font-size: 10px; font-weight: 800;">INVALID</span>
                                <span style="color: var(--text); font-weight: 700; font-size: 16px;">{{ count($invalidRows) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Bulk Actions --}}
                    <div class="actions-group">
                        @if(count($duplicates) > 0)
                            <div class="bulk-actions-wrapper" style="padding-right: 12px; margin-right: 12px; border-right: 1px solid var(--border); display: flex; gap: 8px;">
                                <button wire:click="confirmUpdateAll" style="font-size: 12px; padding: 6px 12px; background: var(--primary-soft); color: var(--primary); border: 1px solid transparent; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                    Update Semua ({{ count($duplicates) }})
                                </button>
                                <button wire:click="confirmSkipAll" style="font-size: 12px; padding: 6px 12px; background: var(--surface-2); color: var(--text-secondary); border: 1px solid var(--border); border-radius: 6px; font-weight: 600; cursor: pointer;">
                                    Skip Semua
                                </button>
                            </div>
                        @endif

                        <button wire:click="confirmCommit" style="padding: 10px 20px; background: var(--success); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Commit Data & Simpan
                        </button>
                    </div>
                </div>

                {{-- Kode Desa Detection --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 8px;">
                     <label style="font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase;">Kode Desa (Default untuk file ini)</label>
                     <div class="kode-desa-wrapper" style="display: flex; gap: 12px;">
                        <input type="text" wire:model="kodeDesa" style="flex: 1; height: 38px; border: 1px solid var(--border); background: var(--bg); border-radius: 8px; padding: 0 12px; color: var(--text); outline: none;" placeholder="Contoh: 72.10.140.010">
                        @if($detectedKodeDesa)
                             <div style="display: flex; align-items: center; gap: 8px; padding: 0 12px; background: var(--success-soft); border-radius: 8px; font-size: 12px; color: var(--success); font-weight: 600;">
                                 ✓ Terdeteksi: {{ $detectedKodeDesa }}
                             </div>
                        @endif
                     </div>
                </div>

                {{-- DATA TABLE Preview --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-sm);">
                    <div style="padding: 16px 20px; background: var(--table-header); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div style="font-size: 12px; color: var(--muted); font-weight: 600; text-transform: uppercase;">
                            Preview Data Import
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary);">
                            <span>Tampilkan:</span>
                            <select wire:model.live="perPage" style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 13px; outline: none; cursor: pointer;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="all">Semua</option>
                            </select>
                            <span>data</span>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="mobile-compact-table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: var(--table-header);">
                                    <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border);">No</th>
                                    <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border);">Status</th>
                                    @foreach(array_keys($preview[0] ?? []) as $k)
                                        @php
                                            $isEssential = (stripos($k, 'NAMA') !== false || stripos($k, 'NOP') !== false);
                                        @endphp
                                        <th class="{{ $isEssential ? '' : 'hide-on-mobile-col' }}" style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); white-space: nowrap;">{{ $k }}</th>
                                    @endforeach
                                    <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); position: sticky; right: 0; background: var(--table-header); z-index: 5;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $limit = $perPage === 'all' ? count($preview) : (int)$perPage;
                                    $slicedPreview = array_slice($preview, 0, $limit, true);
                                @endphp
                                @foreach($slicedPreview as $idx => $r)
                                    @php
                                        if(array_key_exists($idx, $invalidRows)) continue; // Skip invalid rows here
                                        $isDuplicate = array_key_exists($idx, $duplicates);
                                        $rowBg = $isDuplicate ? 'var(--warning-soft)' : 'transparent';
                                        $choice = $userChoices[$idx] ?? 'update';
                                    @endphp
                                    <tr style="border-bottom: 1px solid var(--border-light); background: {{ $rowBg }}; transition: background 0.15s ease;"
                                        onmouseover="this.style.background='{{ $isDuplicate ? 'var(--warning-soft)' : 'var(--table-row-hover)' }}'" 
                                        onmouseout="this.style.background='{{ $rowBg }}'">
                                        <td style="padding: 14px 16px; font-size: 13px; color: var(--text-secondary);">{{ $idx + 1 }}</td>
                                        <td style="padding: 14px 16px; font-size: 13px;">
                                            @if($isDuplicate)
                                                <span style="display: inline-block; padding: 4px 10px; background: var(--warning); color: white; border-radius: 6px; font-size: 11px; font-weight: 600;">DUPLIKAT</span>
                                            @else
                                                <span style="display: inline-block; padding: 4px 10px; background: var(--success); color: white; border-radius: 6px; font-size: 11px; font-weight: 600;">BARU</span>
                                            @endif
                                        </td>
                                        @foreach($r as $colName => $val)
                                            @php
                                                // Handle potential numeric index if keys are not set
                                                $k = is_string($colName) ? $colName : (array_keys($preview[0] ?? [])[$colName] ?? '');
                                                $isEssential = (stripos($k, 'NAMA') !== false || stripos($k, 'NOP') !== false);
                                            @endphp
                                            <td class="{{ $isEssential ? '' : 'hide-on-mobile-col' }}" style="padding: 14px 16px; font-size: 13px; color: var(--text);">{{ $val }}</td>
                                        @endforeach
                                        <td style="padding: 14px 16px; text-align: center; position: sticky; right: 0; background: {{ $rowBg }}; z-index: 5;">
                                            @if($isDuplicate)
                                                <div style="display: flex; gap: 6px; justify-content: center;">
                                                    <button wire:click="setChoice({{ $idx }}, 'update')" 
                                                        style="padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; background: {{ $choice === 'update' ? 'var(--primary)' : 'var(--surface-2)' }}; color: {{ $choice === 'update' ? 'white' : 'var(--text-secondary)' }}; box-shadow: {{ $choice === 'update' ? 'var(--shadow-sm)' : 'none' }};">
                                                        Update
                                                    </button>
                                                    <button wire:click="setChoice({{ $idx }}, 'skip')" 
                                                        style="padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; background: {{ $choice === 'skip' ? 'var(--danger-soft)' : 'var(--surface-2)' }}; color: {{ $choice === 'skip' ? 'var(--danger)' : 'var(--text-secondary)' }}; box-shadow: {{ $choice === 'skip' ? 'none' : 'none' }};">
                                                        Skip
                                                    </button>
                                                </div>
                                            @else
                                                <span style="color: var(--muted);">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($preview) > $limit)
                        <div style="padding: 12px 20px; background: var(--surface-2); border-top: 1px solid var(--border); font-size: 12px; color: var(--text-secondary); text-align: center;">
                            Menampilkan {{ $limit }} dari {{ count($preview) }} data. <br>
                            <span style="color: var(--muted); font-size: 11px;">(Pilih "Semua" di opsi tampilan untuk melihat seluruh data)</span>
                        </div>
                    @endif
                </div>

                {{-- INVALID ROWS --}}
                @if(count($invalidRows) > 0)
                    <div style="background: var(--surface); border: 1px solid var(--danger-soft); border-left: 4px solid var(--danger); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--danger); font-weight: 700; margin-bottom: 12px; font-size: 14px;">⚠️ {{ count($invalidRows) }} Data Tidak Valid (Akan di-skip)</h4>
                        <div style="overflow-x: auto;">
                            <table class="mobile-compact-table" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                <thead>
                                    <tr style="border-bottom: 2px solid var(--border-light);">
                                        <th style="text-align: left; padding: 8px; color: var(--text-secondary);">#</th>
                                        <th style="text-align: left; padding: 8px; color: var(--text-secondary);">Alasan Error</th>
                                        @php
                                            $firstInvalidRow = $preview[array_key_first($invalidRows)] ?? [];
                                        @endphp
                                        @foreach(array_keys($firstInvalidRow) as $k)
                                            @php
                                                $isEssential = (stripos($k, 'NAMA') !== false || stripos($k, 'NOP') !== false);
                                            @endphp
                                             <th class="{{ $isEssential ? '' : 'hide-on-mobile-col' }}" style="text-align: left; padding: 8px; color: var(--muted); text-transform: uppercase;">{{ $k }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $slicedInvalid = array_slice($invalidRows, 0, $limit, true);
                                    @endphp
                                    @foreach($slicedInvalid as $idx => $reason)
                                        @php 
                                            $row = $preview[$idx] ?? []; 
                                        @endphp
                                        <tr style="border-bottom: 1px solid var(--border-light); background: var(--danger-soft); opacity: 0.9;">
                                            <td style="padding: 8px; color: var(--text-secondary);">{{ $idx + 1 }}</td>
                                            <td style="padding: 8px; color: var(--danger); font-weight: 600;">
                                                {{ $reason }}
                                            </td>
                                             @foreach($row as $colName => $cell)
                                                @php
                                                    $k = is_string($colName) ? $colName : (array_keys($preview[0] ?? [])[$colName] ?? '');
                                                    $isEssential = (stripos($k, 'NAMA') !== false || stripos($k, 'NOP') !== false);
                                                @endphp
                                                <td class="{{ $isEssential ? '' : 'hide-on-mobile-col' }}" style="padding: 8px; color: var(--text-secondary);">{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($invalidRows) > $limit)
                             <div style="padding-top: 12px; font-size: 11px; color: var(--muted); text-align: center;">
                                Menampilkan {{ $limit }} dari {{ count($invalidRows) }} error. (Ubah filter di atas untuk melihat lebih banyak)
                            </div>
                        @endif
                    </div>
                @endif
                
                <div style="display: flex; justify-content: flex-end; padding-top: 12px;">
                    <button wire:click="cancelImport" style="padding: 10px 20px; background: transparent; color: var(--muted); font-weight: 600; cursor: pointer; border: none;">Batal & Reset</button>
                </div>

            </div>

        {{-- STEP 1: UPLOAD (Default State) --}}
        @else
            <div class="import-grid">
                
                {{-- Left: Upload Zone --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 40px; text-align: center; box-shadow: var(--shadow-sm); height: 100%;">
                    <div style="display: flex; flex-direction: column; justify-content: center; height: 100%; min-height: 300px;">
                        <input type="file" wire:model="file" accept=".xlsx,.csv" id="fileImport" style="display: none;">
                        <label for="fileImport" class="upload-zone" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 80px; height: 80px; background: var(--primary-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <svg style="width: 40px; height: 40px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <h3 style="font-size: 20px; font-weight: 600; color: var(--text); margin-bottom: 8px;">
                                Klik untuk Upload Excel
                            </h3>
                            <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 16px;">
                                Format yang didukung: <strong>.xlsx</strong> atau <strong>.csv</strong>
                            </p>
                            <div wire:loading wire:target="file" style="margin-top: 12px; font-size: 14px; color: var(--primary); font-weight: 600;">
                                ⏳ Memproses file...
                            </div>
                            @error('file') <div style="margin-top: 12px; font-size: 14px; color: var(--danger);">{{ $message }}</div> @enderror
                        </label>
                    </div>
                </div>

                {{-- Right: Tips --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow-sm);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
                        <div style="width: 36px; height: 36px; background: var(--warning-soft); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px; color: var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--text);">Panduan Format</h4>
                    </div>
                    
                    <div style="margin-bottom: 24px;">
                        <h5 style="font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">1. Kolom Wajib / Disarankan</h5>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            <span style="font-size: 11px; padding: 4px 8px; background: var(--surface-2); border-radius: 4px; color: var(--text-secondary); border: 1px solid var(--border);">NAMA</span>
                            <span style="font-size: 11px; padding: 4px 8px; background: var(--surface-2); border-radius: 4px; color: var(--text-secondary); border: 1px solid var(--border);">NOP</span>
                            <span style="font-size: 11px; padding: 4px 8px; background: var(--surface-2); border-radius: 4px; color: var(--text-secondary); border: 1px solid var(--border);">LATITUDE</span>
                            <span style="font-size: 11px; padding: 4px 8px; background: var(--surface-2); border-radius: 4px; color: var(--text-secondary); border: 1px solid var(--border);">LONGITUDE</span>
                            <span style="font-size: 11px; padding: 4px 8px; background: var(--surface-2); border-radius: 4px; color: var(--text-secondary); border: 1px solid var(--border);">SHAPE</span>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <h5 style="font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">2. Aturan Import</h5>
                        <ul style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; padding-left: 20px; list-style-type: disc;">
                            <li style="margin-bottom: 6px;">Minimal harus ada <b>NAMA</b> atau <b>NOP</b>.</li>
                            <li style="margin-bottom: 6px;">Baris kosong akan otomatis dilewati.</li>
                            <li style="margin-bottom: 6px;">Jika NOP sudah ada, data dianggap <b>DUPLIKAT</b>.</li>
                        </ul>
                    </div>

                    <div>
                        <h5 style="font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">3. Deteksi Kode Desa</h5>
                        <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                            Sistem bisa mendeteksi Kode Desa otomatis jika header file Anda ditulis dengan format: <br>
                            <code style="background: var(--surface-2); padding: 2px 6px; border-radius: 4px; color: var(--primary);">72.10.xxx KODE DESA</code>
                        </p>
                    </div>
                </div>

            </div>
        @endif

    </div>
    </div>
</div>
