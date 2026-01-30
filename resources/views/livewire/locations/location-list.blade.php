<div class="location-list-page" style="min-height: 100vh;">
    <div class="page-wrap">
        
        {{-- Page Header --}}
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 600; color: var(--text); margin: 0 0 4px 0; line-height: 1.3;">
                Data Lokasi
            </h1>
            <p style="font-size: 14px; color: var(--muted); margin: 0;">
                Kelola semua data lokasi dalam sistem
            </p>
        </div>

        {{-- Filter Section --}}
        <div style="background: var(--surface); border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
            <div class="grid-responsive-filters">
                
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px;">
                        Pencarian
                    </label>
                    <div style="position: relative;">
                        <input type="text" wire:model.live.debounce.500ms="search"
                            class="location-list-input"
                            style="width: 100%; height: 40px; padding: 0 14px 0 40px; font-size: 14px; 
                                   border: 1px solid var(--border); border-radius: 10px; 
                                   background: var(--surface-2); color: var(--text);
                                   outline: none; transition: all 0.15s ease;"
                            placeholder="Cari NAMA, NOP, Kode Desa...">
                        <svg style="position: absolute; left: 14px; top: 12px; width: 16px; height: 16px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Kode Desa Filter --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px;">
                        Kode Desa
                    </label>
                    <select wire:model.live="filterKodeDesa"
                        class="location-list-select"
                        style="width: 100%; height: 40px; padding: 0 14px; font-size: 14px; 
                               border: 1px solid var(--border); border-radius: 10px; 
                               background: var(--surface-2); color: var(--text);
                               outline: none; cursor: pointer; transition: all 0.15s ease;">
                        <option value="">Semua</option>
                        @foreach($this->kodeDesaOptions as $kd)
                            <option value="{{ $kd }}">{{ $kd }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Per Page --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px;">
                        Tampilkan
                    </label>
                    <select wire:model.live="perPage"
                        class="location-list-select"
                        style="width: 100%; height: 40px; padding: 0 14px; font-size: 14px; 
                               border: 1px solid var(--border); border-radius: 10px; 
                               background: var(--surface-2); color: var(--text);
                               outline: none; cursor: pointer; transition: all 0.15s ease;">
                        <option value="10">10 data</option>
                        <option value="25">25 data</option>
                        <option value="50">50 data</option>
                        <option value="100">100 data</option>
                    </select>
                </div>
                {{-- Bulk Actions removed as per request --}}
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid-responsive-stats" style="margin-bottom: 20px;">
            {{-- Total Data --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Total Data</p>
                        <p style="font-size: 28px; font-weight: 600; color: var(--text); margin: 0;">{{ number_format($locations->total()) }}</p>
                    </div>
                    <div style="width: 44px; height: 44px; background: var(--primary-soft); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 22px; height: 22px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Halaman --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Halaman</p>
                        <p style="font-size: 28px; font-weight: 600; color: var(--text); margin: 0;">
                            {{ $locations->currentPage() }}<span style="font-size: 16px; color: var(--muted); font-weight: 400;"> / {{ $locations->lastPage() }}</span>
                        </p>
                    </div>
                    <div style="width: 44px; height: 44px; background: var(--success-soft); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 22px; height: 22px; color: var(--success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Ditampilkan --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Ditampilkan</p>
                        <p style="font-size: 28px; font-weight: 600; color: var(--text); margin: 0;">{{ $locations->count() }}</p>
                    </div>
                    <div style="width: 44px; height: 44px; background: var(--warning-soft); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 22px; height: 22px; color: var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table View (Desktop/Tablet) --}}
        <div class="hide-on-mobile" style="background: var(--surface); border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid var(--border); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--table-header);">
                            @auth
                            <th style="padding: 14px 16px; width: 40px; border-bottom: 1px solid var(--border);">
                                <input type="checkbox" wire:model.live="selectAll" class="custom-checkbox">
                            </th>
                            @endauth
                            <th onclick="@this.sortByColumn('kode_desa')" style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none; border-bottom: 1px solid var(--border);">
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    Kode Desa
                                    @if($sortBy === 'kode_desa')
                                        <svg style="width: 14px; height: 14px; color: var(--primary);" fill="currentColor" viewBox="0 0 20 20">
                                            @if($sortDir === 'asc')
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    @endif
                                </span>
                            </th>
                            <th onclick="@this.sortByColumn('nop')" style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none; border-bottom: 1px solid var(--border);">
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    NOP
                                    @if($sortBy === 'nop')
                                        <svg style="width: 14px; height: 14px; color: var(--primary);" fill="currentColor" viewBox="0 0 20 20">
                                            @if($sortDir === 'asc')
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    @endif
                                </span>
                            </th>
                            <th onclick="@this.sortByColumn('nama')" style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; user-select: none; border-bottom: 1px solid var(--border);">
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    Nama
                                    @if($sortBy === 'nama')
                                        <svg style="width: 14px; height: 14px; color: var(--primary);" fill="currentColor" viewBox="0 0 20 20">
                                            @if($sortDir === 'asc')
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    @endif
                                </span>
                            </th>
                            <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border);">Luas</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border);">Sertifikat</th>
                            <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border);">Luas Bangunan</th>

                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($locations as $idx => $loc)
                            <tr class="table-row" style="border-bottom: 1px solid var(--border-light); transition: background 0.15s ease;"
                                onmouseover="this.style.background='var(--table-row-hover)'" 
                                onmouseout="this.style.background='transparent'">
                                @auth
                                <td style="padding: 14px 16px;">
                                    <input type="checkbox" wire:model.live="selected" value="{{ $loc->id }}" class="custom-checkbox">
                                </td>
                                @endauth
                                <td style="padding: 14px 16px; font-size: 13px; color: var(--text-secondary);">
                                    @if($loc->kode_desa)
                                        <span style="display: inline-block; padding: 4px 10px; background: var(--primary-soft); color: var(--primary); border-radius: 6px; font-size: 12px; font-weight: 500;">
                                            {{ $loc->kode_desa }}
                                        </span>
                                    @else
                                        <span style="color: var(--muted);">-</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; font-size: 13px; font-weight: 500; color: var(--text);">{{ trim($loc->nop ?? '') !== '' ? $loc->nop : '-' }}</td>
                                <td style="padding: 14px 16px; font-size: 13px; color: var(--text);">{{ trim($loc->nama ?? '') !== '' ? $loc->nama : '-' }}</td>
                                <td style="padding: 14px 16px; font-size: 13px; color: var(--text-secondary); text-align: right;">
                                    {{ number_format($loc->luas ?? 0, 0) }} <span style="color: var(--muted); font-size: 11px;">m²</span>
                                </td>
                                <td style="padding: 14px 16px; font-size: 13px; color: var(--text-secondary); text-align: center;">
                                    {{ trim($loc->sertpikat ?? '') !== '' ? $loc->sertpikat : '-' }}
                                </td>
                                <td style="padding: 14px 16px; font-size: 13px; color: var(--text-secondary); text-align: right;">
                                    {{ number_format($loc->luas_bangu ?? 0, 0) }} <span style="color: var(--muted); font-size: 11px;">m²</span>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                        {{-- Detail --}}
                                        @php
                                            $detailData = $loc->only(['nama','nop','kode_desa','luas','luas_bangu','sertpikat','njop','status','address','latitude','longitude','user_perum']);
                                            $detailJson = base64_encode(json_encode($detailData));
                                        @endphp
                                        <button onclick="showLocationDetail(JSON.parse(atob('{{ $detailJson }}')))"
                                            title="Detail" class="btn-touch"
                                            style="color: #16a34a;"
                                            onmouseover="this.style.background='#dcfce7'" 
                                            onmouseout="this.style.background='transparent'">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        {{-- View on Map --}}
                                        @if($loc->latitude && $loc->longitude)
                                        <button onclick="openMapWithCoords({{ $loc->latitude }}, {{ $loc->longitude }})" 
                                            title="Lihat di Peta" class="btn-touch"
                                            style="color: var(--primary);"
                                            onmouseover="this.style.background='var(--primary-soft)'" 
                                            onmouseout="this.style.background='transparent'">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        @else
                                        <button onclick="window.Toast && window.Toast.fire({icon: 'info', title: 'Lokasi belum memiliki koordinat'})"
                                            title="Tidak ada koordinat" class="btn-touch"
                                            style="color: var(--muted); cursor: not-allowed;">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        @endif
                                        
                                        {{-- Edit --}}
                                        @can('manage_locations')
                                        <a href="{{ route('locations.edit', $loc->id) }}" 
                                            wire:navigate
                                            title="Edit" class="btn-touch"
                                            style="color: var(--warning);"
                                            onmouseover="this.style.background='var(--warning-soft)'" 
                                            onmouseout="this.style.background='transparent'">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        
                                        {{-- Delete --}}
                                        <button wire:click="$dispatch('confirm-delete', { id: {{ $loc->id }}, nama: '{{ addslashes($loc->nama ?? $loc->nop) }}' })"
                                            title="Hapus" class="btn-touch"
                                            style="color: var(--danger);"
                                            onmouseover="this.style.background='var(--danger-soft)'" 
                                            onmouseout="this.style.background='transparent'">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 64px 16px; text-align: center;">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <div style="width: 64px; height: 64px; background: var(--surface-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                            <svg style="width: 32px; height: 32px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p style="font-size: 16px; font-weight: 500; color: var(--text); margin: 0 0 4px 0;">Tidak ada data</p>
                                        <p style="font-size: 14px; color: var(--muted); margin: 0;">Coba ubah filter atau kata kunci pencarian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (Desktop) --}}
            @if($locations->hasPages())
                <div class="pagination-responsive" style="padding: 16px 20px; border-top: 1px solid var(--border);">
                    <p style="font-size: 13px; color: var(--muted); margin: 0;">
                        Menampilkan {{ $locations->firstItem() }} - {{ $locations->lastItem() }} dari {{ $locations->total() }} data
                    </p>
                    <div style="display: flex; gap: 4px;">
                        {{-- Previous --}}
                        @if($locations->onFirstPage())
                            <span class="btn-touch" style="color: var(--muted); cursor: not-allowed;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        @else
                            <button wire:click="previousPage" class="btn-touch" style="color: var(--text); border: 1px solid var(--border); background: var(--surface);" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='var(--surface)'">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($locations->getUrlRange(max(1, $locations->currentPage() - 2), min($locations->lastPage(), $locations->currentPage() + 2)) as $page => $url)
                            @if($page == $locations->currentPage())
                                <span class="btn-touch" style="background: var(--primary); color: var(--primary-contrast); font-size: 13px; font-weight: 500;">
                                    {{ $page }}
                                </span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="btn-touch" style="background: var(--surface); border: 1px solid var(--border); color: var(--text); font-size: 13px;" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='var(--surface)'">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($locations->hasMorePages())
                            <button wire:click="nextPage" class="btn-touch" style="color: var(--text); border: 1px solid var(--border); background: var(--surface);" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='var(--surface)'">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @else
                            <span class="btn-touch" style="color: var(--muted); cursor: not-allowed;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Mobile Card View --}}
        <div class="show-on-mobile">
            @forelse($locations as $loc)
                <div class="mobile-card-row">
                    {{-- Header with Kode Desa badge --}}
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div>
                            @if($loc->kode_desa)
                                <span style="display: inline-block; padding: 4px 10px; background: var(--primary-soft); color: var(--primary); border-radius: 6px; font-size: 11px; font-weight: 600;">
                                    {{ $loc->kode_desa }}
                                </span>
                            @endif
                        </div>
                        @if($loc->latitude && $loc->longitude)
                            <span style="font-size: 10px; background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 10px; font-weight: 600;">📍 Terpetakan</span>
                        @endif
                    </div>
                    
                    {{-- Main Info --}}
                    <div style="margin-bottom: 12px;">
                        <div class="mobile-card-label">Nama</div>
                        <div class="mobile-card-value" style="font-weight: 600;">{{ trim($loc->nama ?? '') !== '' ? $loc->nama : '-' }}</div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div>
                            <div class="mobile-card-label">NOP</div>
                            <div class="mobile-card-value">{{ trim($loc->nop ?? '') !== '' ? $loc->nop : '-' }}</div>
                        </div>
                        <div>
                            <div class="mobile-card-label">Luas</div>
                            <div class="mobile-card-value">{{ number_format($loc->luas ?? 0, 0) }} m²</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid var(--border);">
                        @php
                            $detailData = $loc->only(['nama','nop','kode_desa','luas','luas_bangu','sertpikat','njop','status','address','latitude','longitude','user_perum']);
                            $detailJson = base64_encode(json_encode($detailData));
                        @endphp
                        <button onclick="showLocationDetail(JSON.parse(atob('{{ $detailJson }}')))" class="btn-touch" style="flex: 1; background: var(--surface-2); color: var(--text); font-size: 12px; font-weight: 500; gap: 6px;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Detail
                        </button>
                        @if($loc->latitude && $loc->longitude)
                        <button onclick="openMapWithCoords({{ $loc->latitude }}, {{ $loc->longitude }})" class="btn-touch" style="flex: 1; background: var(--primary-soft); color: var(--primary); font-size: 12px; font-weight: 500; gap: 6px;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                            Peta
                        </button>
                        @endif
                        @can('manage_locations')
                        <a href="{{ route('locations.edit', $loc->id) }}" wire:navigate class="btn-touch" style="background: var(--warning-soft); color: var(--warning); font-size: 12px; font-weight: 500;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                        <button wire:click="$dispatch('confirm-delete', { id: {{ $loc->id }}, nama: '{{ addslashes($loc->nama ?? $loc->nop) }}' })" class="btn-touch" style="background: var(--danger-soft); color: var(--danger); font-size: 12px; font-weight: 500;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                        @endcan
                    </div>
                </div>
            @empty
                <div style="padding: 40px 16px; text-align: center; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
                    <div style="width: 48px; height: 48px; background: var(--surface-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <svg style="width: 24px; height: 24px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p style="font-size: 14px; color: var(--muted); margin: 0;">Tidak ada data ditemukan</p>
                </div>
            @endforelse

            {{-- Mobile Pagination --}}
            @if($locations->hasPages())
                <div style="margin-top: 16px; padding: 16px; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
                    <p style="font-size: 12px; color: var(--muted); text-align: center; margin: 0 0 12px 0;">
                        {{ $locations->firstItem() }}-{{ $locations->lastItem() }} dari {{ $locations->total() }}
                    </p>
                    <div style="display: flex; justify-content: center; gap: 8px;">
                        @if(!$locations->onFirstPage())
                            <button wire:click="previousPage" class="btn-touch" style="background: var(--surface-2); color: var(--text); border: 1px solid var(--border);">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                        @endif
                        <span style="display: flex; align-items: center; padding: 0 12px; font-size: 13px; color: var(--text); font-weight: 500;">
                            {{ $locations->currentPage() }} / {{ $locations->lastPage() }}
                        </span>
                        @if($locations->hasMorePages())
                            <button wire:click="nextPage" class="btn-touch" style="background: var(--surface-2); color: var(--text); border: 1px solid var(--border);">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Focus states */
        .location-list-input:focus,
        .location-list-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--focus-ring) !important;
        }
        
        /* Placeholder styling */
        .location-list-input::placeholder {
            color: var(--muted);
        }

        /* Checkbox styling */
        .custom-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid var(--muted);
            appearance: none;
            background: var(--surface);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .custom-checkbox:checked {
            background: var(--primary);
            border-color: var(--primary);
        }
        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }

        /* Floating Action Bar */
        .floating-action-bar {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px); /* Hidden by default */
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 12px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 50;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .floating-action-bar.visible {
            transform: translateX(-50%) translateY(0);
        }
        
        /* Responsive grid */
        @media (max-width: 1024px) {
            .location-list-page > div > div:nth-child(2) > div {
                grid-template-columns: 1fr 1fr !important;
            }
        }
        
        @media (max-width: 640px) {
            .location-list-page > div > div:nth-child(2) > div {
                grid-template-columns: 1fr !important;
            }
            .location-list-page > div > div:nth-child(3) {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    {{-- Floating Action Bar (Auth Only) --}}
    @auth
    <div class="floating-action-bar {{ count($selected) > 0 ? 'visible' : '' }}">
        <div style="display: flex; align-items: center; gap: 12px; border-right: 1px solid var(--border); padding-right: 20px;">
            <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600;">
                {{ count($selected) }}
            </div>
            <span style="font-size: 14px; font-weight: 500; color: var(--text);">Data Terpilih</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <button wire:click="$dispatch('confirm-delete-selected')" 
                style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: var(--danger); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                onmouseover="this.style.background='var(--danger-hover)'"
                onmouseout="this.style.background='var(--danger)'">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus Terpilih
            </button>
            <button wire:click="$set('selected', [])" 
                style="padding: 8px 16px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                onmouseover="this.style.borderColor='var(--text)'; this.style.color='var(--text)'"
                onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--muted)'">
                Batal
            </button>
        </div>
    </div>
    @endauth

    @script
    <script>
        window.showLocationDetail = (data) => {
            const formatVal = (v) => {
                if (v === null || v === undefined) return '-';
                if (typeof v === 'string' && v.trim() === '') return '-';
                return v;
            };
            const formatNum = (v) => v ? new Intl.NumberFormat('id-ID').format(v) : '0';
            
            Swal.fire({
                title: 'Detail Lokasi',
                width: 600,
                padding: '24px',
                customClass: {
                    popup: 'rounded-xl'
                },
                html: `
                    <div style="text-align: left; background: var(--surface-2); border-radius: 24px; padding: 24px; margin-bottom: 24px; border: 1px solid var(--border);">
                        <h3 style="font-size: 20px; font-weight: 700; color: var(--text); margin: 0 0 6px;">${formatVal(data.nama)}</h3>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <p style="font-size: 14px; color: var(--muted); margin: 0;">NOP: ${formatVal(data.nop)}</p>
                             <span style="font-size: 12px; background: ${data.latitude ? '#dcfce7' : '#f3f4f6'}; color: ${data.latitude ? '#16a34a' : '#9ca3af'}; padding: 4px 10px; border-radius: 20px; font-weight: 600;">
                                ${data.latitude ? 'Terpetakan' : 'No Coords'}
                            </span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: left;">
                        <!-- Col 1 -->
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Kode Desa</label>
                                <span style="font-size: 14px; font-weight:500; color:var(--text);">${formatVal(data.kode_desa)}</span>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Sertifikat</label>
                                <span style="font-size: 14px; font-weight:500; color:var(--text);">${formatVal(data.sertpikat)}</span>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Luas Tanah</label>
                                <span style="font-size: 15px; font-weight:600; color:var(--text);">${formatNum(data.luas)} <span style="font-size:12px; color:var(--muted);">m²</span></span>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">User Perum</label>
                                <span style="font-size: 14px; font-weight:500; color:var(--text);">${formatVal(data.user_perum)}</span>
                            </div>
                        </div>
                         <!-- Col 2 -->
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                             <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Alamat</label>
                                <span style="font-size: 14px; font-weight:500; color:var(--text);">${formatVal(data.address)}</span>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">NJOP</label>
                                <span style="font-size: 14px; font-weight:500; color:var(--text);">${data.njop ? 'Rp ' + formatNum(data.njop) : '-'}</span>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Luas Bangunan</label>
                                <span style="font-size: 15px; font-weight:600; color:var(--text);">${formatNum(data.luas_bangu)} <span style="font-size:12px; color:var(--muted);">m²</span></span>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight: 600; color:var(--muted); text-transform:uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Koordinat</label>
                                <span style="font-size: 13px; font-family: monospace; color:var(--text-secondary);">${
                                    (data.latitude && data.longitude) 
                                    ? parseFloat(data.latitude).toFixed(6) + ', ' + parseFloat(data.longitude).toFixed(6) 
                                    : '-'
                                }</span>
                            </div>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                background: 'var(--surface)',
                color: 'var(--text)'
            });
        };
        $wire.on('confirm-delete', (data) => {
            confirmSwal({
                title: 'Hapus Data?',
                html: `Data <strong>${data.nama}</strong> akan dihapus permanen.`,
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteLocation(data.id);
                }
            });
        });

        $wire.on('confirm-delete-selected', () => {
            confirmSwal({
                title: 'Hapus Data Terpilih?',
                text: "Data yang dipilih akan dihapus permanen.",
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('execute-delete-selected');
                }
            });
        });

        $wire.on('confirm-delete-all', () => {
             confirmSwal({
                title: 'PERINGATAN BAHAYA!',
                html: "Anda akan menghapus <strong>SELURUH DATA LOKASI</strong> di database.<br>Tindakan ini tidak dapat dibatalkan!",
                icon: 'error',
                confirmButtonText: 'YA, HAPUS SEMUANYA!',
                cancelButtonText: 'Batal, Jangan Lakukan'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Double confirmation
                    confirmSwal({
                        title: 'Yakin Sekali Lagi?',
                        text: "Ketik 'HAPUS' untuk konfirmasi penghapusan massal.",
                        input: 'text',
                        icon: 'warning',
                        confirmButtonText: 'Hapus Sekarang',
                         preConfirm: (value) => {
                            if (value !== 'HAPUS') {
                                Swal.showValidationMessage('Konfirmasi salah. Ketik HAPUS (huruf besar).')
                            }
                        }
                    }).then((res) => {
                        if (res.isConfirmed) {
                            $wire.dispatch('execute-delete-all');
                        }
                    })
                }
            });
        });



        window.openMapWithCoords = (lat, lng) => {
            // Simpan koordinat ke session storage agar URL tetap bersih
            sessionStorage.setItem('map_target_coords', JSON.stringify({ 
                lat: lat, 
                lng: lng, 
                zoom: 18 
            }));
            
            // Redirect ke halaman map tanpa query params
            window.location.href = "{{ route('map') }}";
        }
    </script>
    @endscript
</div>

