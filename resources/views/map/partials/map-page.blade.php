{{-- Leaflet CDN (PASTI KELOAD) --}}
@once
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom scrollbar for sidebar */
        .map-sidebar::-webkit-scrollbar { width: 6px; }
        .map-sidebar::-webkit-scrollbar-track { background: transparent; }
        .map-sidebar::-webkit-scrollbar-thumb { background-color: var(--border); border-radius: 20px; }
        .map-side-input {
            width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border);
            background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: all 0.15s;
        }
        .map-side-input:focus { border-color: var(--primary); box-shadow: 0 0 0 2px var(--focus-ring); background: var(--surface); }
        .map-btn-primary {
            background: var(--primary); color: white; border: none; padding: 10px; border-radius: 8px;
            font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .map-btn-primary:hover { background: var(--primary-hover); }
        .map-btn-secondary {
            background: var(--surface-2); color: var(--text); border: 1px solid var(--border); padding: 10px; border-radius: 8px;
            font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.15s;
        }
        .map-btn-secondary:hover { background: var(--border); }
        
        /* Responsive Map Layout */
        @media (max-width: 768px) {
            .map-page-wrapper {
                flex-direction: column !important;
                height: auto !important;
                padding: 16px !important;
                gap: 16px !important;
            }
            .map-sidebar-panel {
                width: 100% !important;
                max-height: none !important;
            }
            .map-container-panel {
                width: 100% !important;
                min-height: 400px !important;
                order: 3 !important; /* Map goes below detail card */
            }
        }
    </style>
@endonce

<div class="map-page" style="min-height: calc(100vh - 65px); display: flex; flex-direction: column;">
    <div class="map-page-wrapper" style="flex: 1; display: flex; padding: 24px; gap: 24px; max-width: 1600px; margin: 0 auto; width: 100%; height: calc(100vh - 65px);">
        
        {{-- Sidebar Panel --}}
        <div class="map-sidebar-panel" style="width: 400px; flex-shrink: 0; display: flex; flex-direction: column; gap: 16px;">
            
            {{-- Header & Tools Card --}}
            <div style="background: var(--surface); border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                     <h2 style="font-size: 18px; font-weight: 700; color: var(--text); margin: 0;">Pemetaan Wilayah</h2>
                     <div style="display: flex; gap: 8px;">
                         <button id="btnReload" title="Reload Map" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: var(--surface-2); border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer;">
                             <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                         </button>
                     </div>
                </div>

                {{-- Search Inputs --}}
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">Cari (NOP / Nama)</label>
                        <input id="q" type="text" class="map-side-input" placeholder="Ketik nama atau NOP...">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">Latitude</label>
                            <input id="lat" type="text" class="map-side-input" placeholder="-0.xxxx">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">Longitude</label>
                            <input id="lng" type="text" class="map-side-input" placeholder="119.xxxx">
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 4px;">
                        <button id="btnSearch" class="map-btn-primary" style="flex: 1;">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari / Cek Titik
                        </button>
                        <button id="btnClear" class="map-btn-secondary" style="width: 42px; display: flex; align-items: center; justify-content: center;" title="Clear">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div id="hintCount" style="font-size: 12px; color: var(--muted); text-align: center; height: 18px;"></div>
                </div>
            </div>

            {{-- Detail & Results Container (Scrollable) --}}
            <div class="map-sidebar" style="flex: 1; overflow-y: auto; background: var(--surface); border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 20px; display: flex; flex-direction: column; gap: 20px;">
                
                {{-- Detail Box --}}
                <div id="detailContainer">
                    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <span style="width: 20px; height: 2px; background: var(--primary);"></span> Detail Lokasi
                    </h3>
                    <div id="detailBox" style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                        <div style="text-align: center; color: var(--muted); font-size: 13px; padding: 20px 0;">
                            Belum ada lokasi dipilih
                        </div>
                    </div>
                </div>

                {{-- Results List --}}
                <div id="resultsContainer">
                    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <span style="width: 20px; height: 2px; background: var(--warning);"></span> Hasil Pencarian
                    </h3>
                    <div id="results" style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="text-align: center; color: var(--muted); font-size: 13px; font-style: italic;">
                            Hasil pencarian akan muncul di sini
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Map Container --}}
        <div class="map-container-panel" style="flex: 1; background: var(--surface); border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 6px; display: flex; flex-direction: column;">
            <div style="flex: 1; border-radius: 12px; overflow: hidden; position: relative; border: 1px solid var(--border-light);">
                <div id="map" class="absolute inset-0" style="width: 100%; height: 100%; z-index: 1;"></div>
            </div>
            <div style="padding: 8px 12px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--muted);">
                <span>&copy; OpenStreetMap Contributors</span>
                <span>Latitude & Longitude (WGS84)</span>
            </div>
        </div>

    </div>
</div>

<script>
    // Inject Permission Check
    window.canManageLocations = @json(auth()->check() && auth()->user()->can('manage_locations'));

    // Bungkus logic map agar bisa dipanggil ulang
    function initMapPage() {
        // Cek elemen map
        if (!document.getElementById('map')) return;

        // Cek Leaflet
        if (!window.L) {
            console.error('Leaflet not loaded');
            return;
        }

        // --- CLEANUP ---
        const container = document.getElementById('map');
        if (container && container._leaflet_id) {
            container._leaflet_id = null;
        }

        // ===== Utils =====
        const $ = (id) => document.getElementById(id);

        function toast(type, message) {
            if (window.Swal) {
                const color = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#f59e0b');
                Swal.fire({ 
                    toast:true, position:'top-end', icon:type, title:message, 
                    showConfirmButton:false, timer:2200, iconColor: color,
                    customClass: { popup: 'colored-toast' } 
                });
            } else {
                console.log(type.toUpperCase() + ':', message);
            }
        }

        function normNum(v){
            if (v === null || v === undefined) return '';
            return String(v).trim().replace(',', '.');
        }

        function safeLatLng(lat, lng){
            const a = Number(normNum(lat));
            const b = Number(normNum(lng));
            if (!Number.isFinite(a) || !Number.isFinite(b)) return null;
            return { lat: a, lng: b };
        }

        async function fetchJson(url){
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        }

        // Fix icon 404
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

        const defaultLat = -0.9000;
        const defaultLng = 119.8700;

        const map = L.map('map', { zoomControl: false }).setView([defaultLat, defaultLng], 12);
        L.control.zoom({ position: 'topright' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: ''
        }).addTo(map);

        const markersLayer = L.layerGroup().addTo(map);
        let activeMarker = null;

        function clearMarkers(){
            markersLayer.clearLayers();
            activeMarker = null;
        }

        function setActiveMarker(lat, lng, popupHtml){
            if (activeMarker) markersLayer.removeLayer(activeMarker);
            activeMarker = L.marker([lat, lng]).addTo(markersLayer);
            if (popupHtml) activeMarker.bindPopup(popupHtml).openPopup();
            map.setView([lat, lng], 18);
        }

        function setDetail(item){
            const valOrDash = (v) => {
                if (v === null || v === undefined) return '-';
                if (typeof v === 'string' && v.trim() === '') return '-';
                return v;
            };

            if (!item) {
                const db = $('detailBox');
                if(db) db.innerHTML = `
                    <div style="text-align: center; color: var(--muted); font-size: 13px; padding: 20px 0;">
                        Belum ada lokasi dipilih
                    </div>
                `;
                return;
            }

            const id        = item?.id ?? null;
            const nama      = item?.nama ?? item?.name ?? '(Tanpa nama)';
            const kodeDesa  = valOrDash(item?.kode_desa);
            const shape     = valOrDash(item?.shape);
            const nop       = valOrDash(item?.nop);
            const luas      = item?.luas != null ? Number(item.luas).toLocaleString('id-ID') + ' m²' : '-';
            const sertpikat = valOrDash(item?.sertpikat ?? item?.sertifikat);
            const njop      = item?.njop != null ? 'Rp ' + Number(item.njop).toLocaleString('id-ID') : '-';
            const userPerum = valOrDash(item?.user_perum);
            const latStr    = item?.latitude ? Number(item.latitude).toFixed(6) : '-';
            const lngStr    = item?.longitude ? Number(item.longitude).toFixed(6) : '-';

            const actionBtns = (id && window.canManageLocations) ? `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border);">
                    <button type="button" onclick="confirmEditLocation(${id}, '${escapeHtml(nama).replace(/'/g, "\\'")}')"
                            style="background: var(--primary); color: white; border: none; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;">
                        ✏️ Edit
                    </button>
                    <button type="button" onclick="confirmDeleteLocation(${id}, '${escapeHtml(nama).replace(/'/g, "\\'")}')"
                            style="background: var(--danger); color: white; border: none; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;">
                        🗑️ Hapus
                    </button>
                </div>
            ` : '';

            const db = $('detailBox');
            if(db) db.innerHTML = `
                <div style="font-weight: 700; font-size: 15px; color: var(--text); margin-bottom: 8px;">${escapeHtml(nama)}</div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: x-12px; gap-y: 8px; font-size: 12px;">
                     
                     <!-- Row 1 -->
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">Kode Desa</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(kodeDesa))}</div>
                     </div>
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">Shape</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(shape))}</div>
                     </div>

                     <!-- Row 2 -->
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">NOP</div>
                        <div style="color: var(--primary); font-weight: 600;">${escapeHtml(String(nop))}</div>
                     </div>
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">Luas</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(luas))}</div>
                     </div>

                     <!-- Row 3 -->
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">Sertifikat</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(sertpikat))}</div>
                     </div>
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">NJOP</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(njop))}</div>
                     </div>

                     <!-- Row 4 -->
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">Luas Bangunan</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(item.luas_bangu != null ? Number(item.luas_bangu).toLocaleString('id-ID') : '-'))}</div>
                     </div>
                     <div>
                        <div style="color: var(--muted); font-size: 11px;">User Perum</div>
                        <div style="color: var(--text); font-weight: 500;">${escapeHtml(String(userPerum))}</div>
                     </div>
                </div>

                <div style="margin-top: 12px; font-size: 12px;">
                    <div style="color: var(--muted); font-size: 11px;">Koordinat</div>
                    <div style="color: var(--text); font-family: monospace; background: var(--surface-2); padding: 4px 8px; border-radius: 6px; display: inline-block;">
                        ${latStr}, ${lngStr}
                    </div>
                </div>
                
                ${actionBtns}
            `;
        }

        function renderResults(items){
            const el = $('results');
            if(!el) return;
            el.innerHTML = '';
            
            const hc = $('hintCount');
            if(hc) hc.textContent = items.length ? `Ditemukan: ${items.length} lokasi` : '';

            if (items.length === 0) {
                 el.innerHTML = `
                    <div style="text-align: center; color: var(--muted); font-size: 13px; font-style: italic;">
                        Tidak ada data ditemukan
                    </div>
                `;
                return;
            }

            items.forEach((it) => {
                const nama = it?.nama ?? it?.name ?? '(Tanpa nama)';
                const nop  = it?.nop ?? '-';
                const ll = safeLatLng(it?.latitude ?? it?.lat, it?.longitude ?? it?.lng);
                
                const card = document.createElement('button');
                card.type = 'button';
                card.style.cssText = 'width: 100%; text-align: left; padding: 12px; border-radius: 12px; border: 1px solid var(--border); background: var(--surface); transition: all 0.2s; cursor: pointer; display: flex; flex-direction: column; gap: 2px;';
                card.innerHTML = `
                    <div style="font-weight: 600; font-size: 13px; color: var(--text);">${escapeHtml(nama)}</div>
                    <div style="font-size: 11px; color: var(--muted);">NOP: ${escapeHtml(String(nop))}</div>
                `;
                
                card.onmouseover = () => { card.style.background = 'var(--surface-2)'; card.style.borderColor = 'var(--primary)'; };
                card.onmouseout = () => { card.style.background = 'var(--surface)'; card.style.borderColor = 'var(--border)'; };
                
                card.addEventListener('click', () => {
                    const latIn = $('lat'), lngIn = $('lng');
                    if(latIn) latIn.value = ll ? ll.lat.toFixed(6) : '';
                    if(lngIn) lngIn.value = ll ? ll.lng.toFixed(6) : '';
                    setDetail({ ...it, latitude: ll?.lat, longitude: ll?.lng });
                    if (ll) setActiveMarker(ll.lat, ll.lng, getPopupContent(nama, nop, ll.lat, ll.lng));
                    
                    // Highlight logic could go here
                });
                el.appendChild(card);
            });
        }

        function getPopupContent(nama, nop, lat, lng) {
            return `
                <div style="min-width:180px; font-family: sans-serif;">
                    <b style="font-size:14px; color:#333;">${escapeHtml(nama)}</b><br>
                    <span style="font-size:12px; color:#666;">NOP: ${escapeHtml(String(nop))}</span>
                    <div style="margin-top:8px; padding-top:8px; border-top:1px solid #eee; display:flex; gap:12px;">
                         <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" style="text-decoration:none; color:#2563eb; font-size:11px; font-weight:600;">Google Maps ↗</a>
                         <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" target="_blank" style="text-decoration:none; color:#059669; font-size:11px; font-weight:600;">Rute 🚗</a>
                    </div>
                </div>
            `;
        }

        async function performSearch() {
            const qInput = $('q');
            const latInput = $('lat');
            const lngInput = $('lng');
            const hc = $('hintCount');
            
            const q = qInput ? qInput.value.trim() : '';
            const latRaw = latInput ? latInput.value.trim() : '';
            const lngRaw = lngInput ? lngInput.value.trim() : '';
            
            if (!q && !latRaw && !lngRaw) {
                toast('warning', 'Masukkan kata kunci atau koordinat');
                return;
            }
            
            if(hc) hc.textContent = 'Mencari...';
            const ll = safeLatLng(latRaw, lngRaw);
            
            // Logic: jika ada lat/lng, cari exact check dulu
            if (latRaw && lngRaw && ll) {
                try {
                    const url = new URL('/ajax/locations/check', window.location.origin);
                    url.searchParams.set('lat', ll.lat);
                    url.searchParams.set('lng', ll.lng);
                    if (q) url.searchParams.set('q', q);
                    
                    const response = await fetchJson(url.toString());
                    if (response?.found) {
                        toast('success', 'Lokasi ditemukan');
                        setDetail(response.data);
                        setActiveMarker(Number(response.data.latitude), Number(response.data.longitude), getPopupContent(response.data.nama, response.data.nop, response.data.latitude, response.data.longitude));
                        if(hc) hc.textContent = '1 Lokasi Ditemukan';
                        renderResults([response.data]);
                        return; // Done
                    }
                } catch(e) { console.error('Exact check error', e); }
            }
            
            // Fallback search
            try {
                const url = new URL('/ajax/locations', window.location.origin);
                if (q) url.searchParams.set('q', q);
                if (latRaw) url.searchParams.set('lat', latRaw);
                if (lngRaw) url.searchParams.set('lng', lngRaw);
                
                const data = await fetchJson(url.toString());
                const items = Array.isArray(data?.data) ? data.data : (Array.isArray(data?.locations) ? data.locations : []);
                
                clearMarkers();
                renderResults(items);
                
                if (items.length === 0) {
                    toast('info', 'Tidak ada data ditemukan');
                    if(hc) hc.textContent = '0 Hasil';
                } else {
                    toast('success', `${items.length} data ditemukan`);
                }
            } catch(e) {
                console.error(e);
                toast('error', 'Gagal memuat data');
            }
        }

        // ===== EDIT & DELETE HELPERS =====
        window.confirmEditLocation = async function(id, nama) {
             const result = await Swal.fire({
                icon: 'question', title: 'Edit Lokasi?',
                text: nama,
                showCancelButton: true, confirmButtonText: 'Ya, Edit', cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6', cancelButtonColor: '#64748b'
            });
            if (result.isConfirmed) window.location.href = `/locations/${id}/edit?source=map`; 
        };

        window.confirmDeleteLocation = async function(id, nama) {
            const result = await Swal.fire({
                icon: 'warning', title: 'Hapus Lokasi?',
                html: `Data <b>${nama}</b> akan dihapus permanen.`,
                showCancelButton: true, confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b'
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`/ajax/locations/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire({ toast:true, icon: 'success', title: 'Terhapus', position:'top-end', showConfirmButton:false, timer:2000 });
                    
                    // Hide detail
                    setDetail(null);
                    
                    // Remove from map if exists
                    // Remove from map if exists
                    if(typeof activeMarker !== 'undefined' && activeMarker) {
                        markersLayer.removeLayer(activeMarker);
                        activeMarker = null;
                    }

                    // Refresh search without clearing if query exists
                    // Refresh search without clearing if query exists
                    // Refresh search logic
                    const qObj = $('q');
                    // We clear lat/lng so the search is not constrained to the deleted point
                    const latObj = $('lat');
                    const lngObj = $('lng');
                    if(latObj) latObj.value = '';
                    if(lngObj) lngObj.value = '';

                    // If there is a query, re-run search to show remaining items
                    if (qObj && qObj.value && typeof performSearch === 'function') {
                         performSearch();
                    } else if (typeof clearMarkers === 'function') {
                         // If no query, just clear markers (or reload default)
                         clearMarkers();
                         if($('results')) $('results').innerHTML = '<div style="text-align:center; padding:20px; color:var(--muted);">Data telah dihapus.</div>';
                         if(hc) hc.textContent = '';
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
            }
        };

        function escapeHtml(str){
            return String(str).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');
        }

        // Event Listeners
        const btnSearch = $('btnSearch');
        if(btnSearch) btnSearch.addEventListener('click', performSearch);
        
        ['q', 'lat', 'lng'].forEach(id => {
            const el = $(id);
            if(el) el.addEventListener('keypress', (e) => {
                if(e.key === 'Enter') { e.preventDefault(); performSearch(); }
            });
        });

        const btnClear = $('btnClear');
        if(btnClear) btnClear.addEventListener('click', () => {
             $('q').value = ''; $('lat').value = ''; $('lng').value = '';
             $('results').innerHTML = `<div style="text-align: center; color: var(--muted); font-size: 13px; font-style: italic;">Reset...</div>`;
             $('hintCount').textContent = '';
             setDetail(null);
             clearMarkers();
             map.setView([defaultLat, defaultLng], 12);
        });
        
        const btnReload = $('btnReload');
        if(btnReload) btnReload.addEventListener('click', () => window.location.reload());

        window.currentMapSearch = performSearch;
        window.searchLocation = performSearch;

        // Auto-Run from Session Storage (Clean URL) or URL Params (Fallback)
        const storedCoords = sessionStorage.getItem('map_target_coords');
        
        if (storedCoords) {
            try {
                const { lat, lng } = JSON.parse(storedCoords);
                if (lat && lng) {
                    $('lat').value = lat;
                    $('lng').value = lng;
                    setTimeout(performSearch, 500);
                }
            } catch(e) { console.error('Invalid map coords', e); }
            
            // Clear immediately so it doesn't persist on reload
            sessionStorage.removeItem('map_target_coords');
        } else {
            // Fallback: URL Params (legacy support)
            const urlParams = new URLSearchParams(window.location.search);
            const pLat = urlParams.get('lat');
            const pLng = urlParams.get('lng');
            if (pLat && pLng) {
                $('lat').value = pLat;
                $('lng').value = pLng;
                setTimeout(performSearch, 500);
            }
        }
    }

    initMapPage();
    // document.addEventListener('livewire:navigated', initMapPage); // Removed to prevent double execution on finding/not finding locations
</script>
