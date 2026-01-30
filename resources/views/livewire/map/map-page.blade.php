@php
    $initial = [
        'categories' => $categories,
        'permissions' => $permissions,
        'routes' => $routes,
        // Center Palu
        'center' => ['lat' => -0.898600, 'lng' => 119.870700],
        'zoom' => 12,
    ];
@endphp

<!-- Leaflet + Plugins (CDN). Works well on cPanel / shared hosting. -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<div x-data="mapApp(@js($initial))" x-init="init()" class="h-[calc(100vh-3.5rem)]">
    <div class="grid grid-cols-1 lg:grid-cols-4 h-full">
        <!-- Sidebar -->
        <div class="lg:col-span-1 border-r bg-white h-full flex flex-col">
            <div class="p-4 border-b space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">Pemetaan Titik</h2>
                    <template x-if="state.permissions.canCreate">
                        <button @click="openCreateDrawer()" class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-gray-800 text-sm">+ Tambah</button>
                    </template>
                </div>

                <div class="space-y-2">
                    <input x-model="state.q" @input.debounce.350ms="refresh(true)" placeholder="Cari nama / NOP / alamat..." class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />

                    <div class="flex gap-2">
                        <select x-model="state.category" @change="refresh(true)" class="w-full rounded-lg border-gray-300">
                            <option value="">Semua kategori</option>
                            <template x-for="c in state.categories" :key="c.id">
                                <option :value="c.id" x-text="c.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <input x-model="state.checkLat" placeholder="Lat" class="rounded-lg border-gray-300" />
                        <input x-model="state.checkLng" placeholder="Lng" class="rounded-lg border-gray-300" />
                    </div>

                    <div class="flex gap-2">
                        <button @click="checkExactFromInputs()" class="flex-1 px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800 text-sm">Cek Titik</button>
                        <button @click="panToInputs()" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm">Go</button>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" x-model="state.exactMode" class="rounded border-gray-300" />
                        Klik peta = cek titik persis
                    </label>

                    <div class="flex gap-2">
                        <button @click="openBulkCheck()" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm">Bulk Check File</button>
                        <button @click="resetFilters()" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm">Reset</button>
                    </div>

                    <div class="pt-2 border-t">
                        <div class="text-xs text-gray-500">Export sesuai filter:</div>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <a :href="exportUrl(state.routes.exportExcel)" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm text-center">Excel</a>
                            <a :href="exportUrl(state.routes.exportCsv)" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm text-center">CSV</a>
                            <a :href="exportUrl(state.routes.exportGeojson)" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm text-center">GeoJSON</a>
                            <a :href="exportUrl(state.routes.exportPdf)" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm text-center">PDF</a>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">
                        Loaded: <b x-text="state.items.length"></b>
                    </div>
                </div>
            </div>

            <!-- List -->
            <div class="flex-1 overflow-y-auto">
                <template x-if="state.loading">
                    <div class="p-4 text-sm text-gray-500">Loading...</div>
                </template>

                <template x-if="!state.loading && state.items.length === 0">
                    <div class="p-4 text-sm text-gray-500">Tidak ada data</div>
                </template>

                <template x-for="it in state.items" :key="it.id">
                    <button @click="focus(it)" class="w-full text-left px-4 py-3 border-b hover:bg-gray-50">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-medium text-sm" x-text="it.nama ?? '(tanpa nama)'"></div>
                                <div class="text-xs text-gray-500" x-text="it.nop ?? ''"></div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" x-text="(it.latitude + ', ' + it.longitude)"></div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Map -->
        <div class="lg:col-span-3 h-full relative">
            <div id="map" class="h-full"></div>

            <!-- Right Drawer (Detail / Edit) -->
            <div x-show="state.drawerOpen" x-transition.opacity class="absolute inset-0 bg-black/20" @click="closeDrawer()"></div>

            <div x-show="state.drawerOpen" x-transition class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-white shadow-xl border-l overflow-y-auto">
                <div class="p-4 border-b flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500" x-text="state.drawerMode === 'create' ? 'Tambah Titik' : (state.drawerMode === 'edit' ? 'Edit Titik' : 'Detail Titik')"></div>
                        <div class="font-semibold" x-text="state.form.nama || '(tanpa nama)'"></div>
                    </div>
                    <button @click="closeDrawer()" class="p-2 rounded-lg hover:bg-gray-50" aria-label="close">
                        ✕
                    </button>
                </div>

                <div class="p-4 space-y-4">
                    <!-- Quick actions -->
                    <template x-if="state.drawerMode !== 'create'">
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="copyCoords()" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm">Copy</button>
                            <a :href="gmapsUrl()" target="_blank" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm text-center">GMaps</a>
                            <a :href="routeUrl()" target="_blank" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm text-center">Route</a>
                        </div>
                    </template>

                    <!-- Form fields -->
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-500">Nama</label>
                            <input x-model="state.form.nama" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                        </div>

                        <div>
                            <label class="text-xs text-gray-500">NOP (unik, boleh kosong)</label>
                            <input x-model="state.form.nop" :disabled="!canEditDrawer()" @input.debounce.400ms="checkNopAvailability()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            <template x-if="state.nopAvailable === false">
                                <div class="text-xs text-red-600 mt-1">NOP sudah dipakai.</div>
                            </template>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500">Latitude</label>
                                <input x-model="state.form.latitude" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Longitude</label>
                                <input x-model="state.form.longitude" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                        </div>

                        <div class="flex gap-2 mt-2">
                            <button @click="moveMarkerFromForm()" class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm">Zoom ke titik</button>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500">Kategori</label>
                                <select x-model="state.form.category_id" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100">
                                    <option value="">(kosong)</option>
                                    <template x-for="c in state.categories" :key="c.id">
                                        <option :value="c.id" x-text="c.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">LUAS</label>
                                <input x-model="state.form.luas" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500">SERTPIKAT</label>
                                <input x-model="state.form.sertpikat" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">NJOP</label>
                                <input x-model="state.form.njop" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500">LUAS_BANGU</label>
                                <input x-model="state.form.luas_bangu" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">USER_PERUM</label>
                                <input x-model="state.form.user_perum" :disabled="!canEditDrawer()" class="mt-1 w-full rounded-lg border-gray-300 disabled:bg-gray-100" />
                            </div>
                        </div>

                        <!-- Photos -->
                        <div class="rounded-lg border p-3">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium">Foto</div>
                                <template x-if="canEditDrawer()">
                                    <label class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm cursor-pointer">
                                        + Tambah
                                        <input type="file" class="hidden" multiple accept="image/*" @change="onPickPhotos($event)" />
                                    </label>
                                </template>
                            </div>

                            <template x-if="state.form.photos && state.form.photos.length">
                                <div class="mt-3 grid grid-cols-3 gap-2">
                                    <template x-for="(p,idx) in state.form.photos" :key="idx">
                                        <img :src="p.url" class="w-full h-24 object-cover rounded-lg border" />
                                    </template>
                                </div>
                            </template>
                            <template x-if="!state.form.photos || state.form.photos.length === 0">
                                <div class="text-xs text-gray-500 mt-2">Belum ada foto.</div>
                            </template>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex gap-2 pt-2 border-t">
                        <template x-if="state.drawerMode === 'create'">
                            <button @click="saveCreate()" class="flex-1 px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800">Simpan</button>
                        </template>
                        <template x-if="state.drawerMode === 'edit'">
                            <button @click="saveEdit()" class="flex-1 px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800">Simpan</button>
                        </template>
                        <template x-if="state.drawerMode === 'detail' && state.permissions.canEdit">
                            <button @click="switchToEdit()" class="flex-1 px-3 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800">Edit</button>
                        </template>
                        <template x-if="state.drawerMode !== 'create' && state.permissions.canDelete">
                            <button @click="deleteLocation()" class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-500">Hapus</button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mapApp(init) {
  return {
    state: {
      ...init,
      q: '',
      category: '',
      exactMode: false,
      loading: false,
      items: [],
      checkLat: '',
      checkLng: '',

      drawerOpen: false,
      drawerMode: 'detail',
      selectedId: null,
      form: {
        id: null,
        fid: null,
        shape: null,
        nama: '',
        nop: '',
        luas: '',
        sertpikat: '',
        njop: '',
        luas_bangu: '',
        user_perum: '',
        latitude: '',
        longitude: '',
        category_id: '',
        photos: [],
      },

      nopAvailable: null,
      pickedFiles: [],
    },

    map: null,
    cluster: null,
    markers: new Map(),

    init() {
      this.map = L.map('map').setView([this.state.center.lat, this.state.center.lng], this.state.zoom);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(this.map);

      this.cluster = L.markerClusterGroup({
        maxClusterRadius: 50,
        showCoverageOnHover: false,
      });
      this.map.addLayer(this.cluster);

      if (window.L && L.Control && L.Control.Geocoder) {
        L.Control.geocoder({ defaultMarkGeocode: false })
          .on('markgeocode', (e) => {
            const c = e.geocode.center;
            this.map.setView([c.lat, c.lng], 18);
          })
          .addTo(this.map);
      }

      this.map.on('moveend', () => this.refresh(true));
      this.map.on('click', (e) => {
        if (!this.state.exactMode) return;
        this.checkExact(e.latlng.lat, e.latlng.lng);
      });

      this.refresh(true);
    },

    resetFilters() {
      this.state.q = '';
      this.state.category = '';
      this.state.checkLat = '';
      this.state.checkLng = '';
      this.state.exactMode = false;
      this.refresh(true);
    },

    exportUrl(baseUrl) {
      const u = new URL(baseUrl, window.location.origin);
      if (this.state.q) u.searchParams.set('q', this.state.q);
      if (this.state.category) u.searchParams.set('category_id', this.state.category);
      return u.toString();
    },

    panToInputs() {
      const lat = parseFloat(this.state.checkLat);
      const lng = parseFloat(this.state.checkLng);
      if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        this.toast('info', 'Isi Lat/Lng dulu');
        return;
      }
      this.map.setView([lat, lng], 18);
    },

    checkExactFromInputs() {
      const lat = parseFloat(this.state.checkLat);
      const lng = parseFloat(this.state.checkLng);
      if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        this.toast('info', 'Isi Lat/Lng valid');
        return;
      }
      this.checkExact(lat, lng);
    },

    async refresh(useBbox = false) {
      this.state.loading = true;

      const params = new URLSearchParams();
      if (this.state.q) params.set('q', this.state.q);
      if (this.state.category) params.set('category_id', this.state.category);

      if (useBbox && this.map) {
        const b = this.map.getBounds();
        params.set('bbox', `${b.getWest()},${b.getSouth()},${b.getEast()},${b.getNorth()}`);
      }

      const url = `${this.state.routes.markers}?${params.toString()}`;
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      this.state.items = json.data ?? [];
      this.state.loading = false;

      this.syncMarkers();
    },

    syncMarkers() {
      const keep = new Set(this.state.items.map(i => i.id));

      for (const [id, marker] of this.markers.entries()) {
        if (!keep.has(id)) {
          this.cluster.removeLayer(marker);
          this.markers.delete(id);
        }
      }

      for (const it of this.state.items) {
        if (!it.latitude || !it.longitude) continue;
        const lat = parseFloat(it.latitude);
        const lng = parseFloat(it.longitude);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) continue;

        if (this.markers.has(it.id)) continue;

        const m = L.marker([lat, lng]);
        m.on('click', () => this.openDetailById(it.id));
        this.cluster.addLayer(m);
        this.markers.set(it.id, m);
      }
    },

    focus(it) {
      const lat = parseFloat(it.latitude);
      const lng = parseFloat(it.longitude);
      if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
      this.map.setView([lat, lng], 18);
      this.openDetailById(it.id);
    },

    openDrawer(mode) {
      this.state.drawerMode = mode;
      this.state.drawerOpen = true;
      document.body.style.overflow = 'hidden';
    },

    closeDrawer() {
      this.state.drawerOpen = false;
      document.body.style.overflow = '';
      this.state.pickedFiles = [];
      this.state.nopAvailable = null;
    },

    canEditDrawer() {
      if (this.state.drawerMode === 'create') return this.state.permissions.canCreate;
      if (this.state.drawerMode === 'edit') return this.state.permissions.canEdit;
      return false;
    },

    async openDetailById(id) {
      this.state.selectedId = id;
      const url = `${this.state.routes.showBase}/${id}`;
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) {
        this.toast('error', 'Gagal memuat detail');
        return;
      }
      const json = await res.json();
      const loc = json.data;

      this.state.form = this.normalizeForm(loc);
      this.openDrawer('detail');
    },

    normalizeForm(loc) {
      return {
        id: loc.id,
        fid: loc.fid ?? null,
        shape: loc.shape ?? null,
        nama: loc.nama ?? '',
        nop: loc.nop ?? '',
        luas: loc.luas ?? '',
        sertpikat: loc.sertpikat ?? '',
        njop: loc.njop ?? '',
        luas_bangu: loc.luas_bangu ?? '',
        user_perum: loc.user_perum ?? '',
        latitude: loc.latitude ?? '',
        longitude: loc.longitude ?? '',
        category_id: loc.category_id ?? '',
        photos: (loc.photos ?? []).map(p => ({ id: p.id, url: `/storage/${p.path}` })),
      };
    },

    switchToEdit() {
      if (!this.state.permissions.canEdit) return;
      this.state.drawerMode = 'edit';
      this.state.nopAvailable = null;
    },

    openCreateDrawer() {
      this.state.form = {
        id: null,
        fid: null,
        shape: null,
        nama: '',
        nop: '',
        luas: '',
        sertpikat: '',
        njop: '',
        luas_bangu: '',
        user_perum: '',
        latitude: this.state.checkLat || '',
        longitude: this.state.checkLng || '',
        category_id: '',
        photos: [],
      };
      this.state.pickedFiles = [];
      this.openDrawer('create');
    },

    async checkNopAvailability() {
      if (!this.canEditDrawer()) return;

      const nop = (this.state.form.nop || '').trim();
      const url = new URL(this.state.routes.nopAvailable, window.location.origin);
      url.searchParams.set('nop', nop);
      if (this.state.form.id) url.searchParams.set('exclude_id', this.state.form.id);

      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return;
      const j = await res.json();
      this.state.nopAvailable = j.available;
    },

    onPickPhotos(e) {
      const files = Array.from(e.target.files || []);
      this.state.pickedFiles = files;

      const previews = [];
      for (const f of files) previews.push({ url: URL.createObjectURL(f) });
      this.state.form.photos = (this.state.form.photos || []).concat(previews);
    },

    validateFormBasic() {
      const lat = parseFloat(this.state.form.latitude);
      const lng = parseFloat(this.state.form.longitude);
      if (!Number.isFinite(lat) || lat < -90 || lat > 90) return 'Latitude tidak valid';
      if (!Number.isFinite(lng) || lng < -180 || lng > 180) return 'Longitude tidak valid';
      if (this.state.nopAvailable === false) return 'NOP sudah dipakai';
      return null;
    },

    async saveCreate() {
      if (!this.state.permissions.canCreate) return;
      const err = this.validateFormBasic();
      if (err) { this.toast('error', err); return; }

      const ok = await this.confirm('Simpan titik?', 'Data akan dibuat sebagai Draft.');
      if (!ok) return;

      const res = await this.createLocation();
      if (!res) return;

      this.toast('success', 'Tersimpan');
      this.closeDrawer();
      await this.refresh(true);
    },

    async saveEdit() {
      if (!this.state.permissions.canEdit || !this.state.form.id) return;
      const err = this.validateFormBasic();
      if (err) { this.toast('error', err); return; }

      const ok = await this.confirm('Simpan perubahan?', 'Perubahan akan diterapkan.');
      if (!ok) return;

      const res = await this.updateLocation(this.state.form.id);
      if (!res) return;

      this.toast('success', 'Diupdate');
      await this.openDetailById(this.state.form.id);
      await this.refresh(true);
    },

    async deleteLocation() {
      if (!this.state.permissions.canDelete || !this.state.form.id) return;
      const ok = await this.confirm('Hapus titik?', 'Aksi ini tidak bisa dibatalkan.', 'warning');
      if (!ok) return;

      const url = `${this.state.routes.deleteBase}/${this.state.form.id}`;
      const res = await fetch(url, {
        method: 'DELETE',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
      });

      if (!res.ok) {
        this.toast('error', 'Gagal menghapus');
        return;
      }

      this.toast('success', 'Terhapus');
      this.closeDrawer();
      await this.refresh(true);
    },

    async submitForApproval() {
      if (!this.state.permissions.canEdit || !this.state.form.id) return;
      const ok = await this.confirm('Submit untuk approval?', 'Status akan menjadi Pending.');
      if (!ok) return;

      const url = `${this.state.routes.submitBase}/${this.state.form.id}/submit`;
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
      });

      if (!res.ok) { this.toast('error', 'Gagal submit'); return; }
      this.toast('success', 'Submitted');
      await this.openDetailById(this.state.form.id);
      await this.refresh(true);
    },

    async approve() {
      if (!this.state.permissions.canApprove || !this.state.form.id) return;
      const ok = await this.confirm('Approve titik?', 'Status akan menjadi Published.');
      if (!ok) return;

      const url = `${this.state.routes.approveBase}/${this.state.form.id}/approve`;
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
      });

      if (!res.ok) { this.toast('error', 'Gagal approve'); return; }
      this.toast('success', 'Approved');
      await this.openDetailById(this.state.form.id);
      await this.refresh(true);
    },

    async unpublish() {
      if (!this.state.permissions.canApprove || !this.state.form.id) return;
      const ok = await this.confirm('Unpublish titik?', 'Status akan menjadi Pending.');
      if (!ok) return;

      const url = `${this.state.routes.unpublishBase}/${this.state.form.id}/unpublish`;
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
      });

      if (!res.ok) { this.toast('error', 'Gagal unpublish'); return; }
      this.toast('success', 'Unpublished');
      await this.openDetailById(this.state.form.id);
      await this.refresh(true);
    },

    async createLocation() {
      const fd = this.buildFormData(false);
      const res = await fetch(this.state.routes.store, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: fd,
      });

      if (res.status === 422) {
        const j = await res.json();
        this.toast('error', firstValidationMessage(j));
        return null;
      }
      if (!res.ok) {
        this.toast('error', 'Gagal menyimpan');
        return null;
      }
      return await res.json();
    },

    async updateLocation(id) {
      const fd = this.buildFormData(true);
      fd.append('_method', 'PUT');

      const url = `${this.state.routes.updateBase}/${id}`;
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: fd,
      });

      if (res.status === 422) {
        const j = await res.json();
        this.toast('error', firstValidationMessage(j));
        return null;
      }
      if (!res.ok) {
        this.toast('error', 'Gagal update');
        return null;
      }
      return await res.json();
    },

    buildFormData(isUpdate) {
      const fd = new FormData();
      const payload = {
        fid: this.state.form.fid,
        shape: this.state.form.shape,
        nama: this.state.form.nama,
        nop: this.state.form.nop,
        luas: this.state.form.luas,
        sertpikat: this.state.form.sertpikat,
        njop: this.state.form.njop,
        luas_bangu: this.state.form.luas_bangu,
        user_perum: this.state.form.user_perum,
        latitude: this.state.form.latitude,
        longitude: this.state.form.longitude,
        category_id: this.state.form.category_id || '',
      };

      for (const [k, v] of Object.entries(payload)) {
        if (v === undefined || v === null) continue;
        if (!isUpdate && v === '') continue;
        fd.append(k, v);
      }

      for (const f of (this.state.pickedFiles || [])) {
        fd.append('photos[]', f);
      }

      return fd;
    },

    moveMarkerFromForm() {
      const lat = parseFloat(this.state.form.latitude);
      const lng = parseFloat(this.state.form.longitude);
      if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
      this.map.setView([lat, lng], 18);
    },

    async reverseFillAddress() {
      const lat = parseFloat(this.state.form.latitude);
      const lng = parseFloat(this.state.form.longitude);
      if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        this.toast('info', 'Lat/Lng tidak valid');
        return;
      }
      const url = new URL(this.state.routes.reverse, window.location.origin);
      url.searchParams.set('lat', lat);
      url.searchParams.set('lng', lng);

      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const j = await res.json();
      if (!j.address) {
        this.toast('info', 'Alamat tidak ditemukan');
        return;
      }
      this.state.form.address = j.address;
      this.toast('success', 'Alamat terisi');
    },

    async checkExact(lat, lng) {
      const url = new URL(this.state.routes.checkExact, window.location.origin);
      url.searchParams.set('lat', lat);
      url.searchParams.set('lng', lng);
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (!json.found) {
        this.toast('info', 'Tidak ada');
        return;
      }
      this.state.checkLat = json.data.latitude;
      this.state.checkLng = json.data.longitude;
      await this.openDetailById(json.data.id);
    },

    openBulkCheck() {
      Swal.fire({
        title: 'Bulk Check (Excel/CSV)',
        html: `
          <div style="text-align:left;font-size:13px;color:#444">
            Upload file yang berisi kolom <b>LATITUDE</b> dan <b>LONGTITUDE</b> (atau LONGITUDE).
          </div>
          <input id="bulk_file" type="file" class="swal2-file" accept=".xlsx,.csv" />
        `,
        showCancelButton: true,
        confirmButtonText: 'Proses',
        preConfirm: () => {
          const f = document.getElementById('bulk_file')?.files?.[0];
          if (!f) {
            Swal.showValidationMessage('Pilih file dulu');
            return false;
          }
          return f;
        }
      }).then(async (r) => {
        if (!r.isConfirmed) return;
        const file = r.value;
        await this.runBulkCheck(file);
      });
    },

    async runBulkCheck(file) {
      const fd = new FormData();
      fd.append('file', file);

      const res = await fetch(this.state.routes.bulkCheck, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: fd,
      });

      if (res.status === 422) {
        const j = await res.json();
        this.toast('error', j.message || 'Gagal');
        return;
      }

      if (!res.ok) {
        this.toast('error', 'Gagal bulk check');
        return;
      }

      const j = await res.json();
      const rows = j.data || [];
      const foundCount = rows.filter(x => x.found).length;
      const notFoundCount = rows.length - foundCount;

      const lines = rows.slice(0, 50).map(x => {
        if (x.found) {
          const d = x.data;
          return `✅ row ${x.row}: ${escapeHtml(d.nama || '(tanpa nama)')} (${escapeHtml(d.nop || '-')})`;
        }
        return `❌ row ${x.row}: NOT FOUND`;
      });

      Swal.fire({
        title: 'Hasil Bulk Check',
        html: `
          <div style="text-align:left">
            <div><b>Found:</b> ${foundCount} | <b>Not found:</b> ${notFoundCount}</div>
            <div style="margin-top:10px;max-height:280px;overflow:auto;border:1px solid #eee;padding:10px;border-radius:8px;font-size:12px;line-height:1.35">
              ${lines.map(l => `<div>${l}</div>`).join('')}
            </div>
            <div style="margin-top:8px;font-size:11px;color:#666">Menampilkan max 50 baris pertama.</div>
          </div>
        `,
        showCloseButton: true,
        confirmButtonText: 'OK'
      });
    },

    gmapsUrl() {
      if (!this.state.form.latitude || !this.state.form.longitude) return '#';
      return `https://www.google.com/maps?q=${encodeURIComponent(this.state.form.latitude)},${encodeURIComponent(this.state.form.longitude)}`;
    },

    routeUrl() {
      if (!this.state.form.latitude || !this.state.form.longitude) return '#';
      return `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(this.state.form.latitude)},${encodeURIComponent(this.state.form.longitude)}`;
    },

    copyCoords() {
      const t = `${this.state.form.latitude},${this.state.form.longitude}`;
      if (!navigator.clipboard) {
        this.toast('info', t);
        return;
      }
      navigator.clipboard.writeText(t).then(() => this.toast('success', 'Copied'));
    },

    async confirm(title, text, icon = 'question') {
      if (!window.Swal) return confirm(title);
      const r = await Swal.fire({ title, text, icon, showCancelButton: true, confirmButtonText: 'Ya' });
      return r.isConfirmed;
    },

    toast(type, message) {
      if (!window.Swal) return alert(message);
      Swal.fire({ toast:true, position:'top-end', icon:type, title:message, showConfirmButton:false, timer:2300, timerProgressBar:true });
    }
  };
}

function escapeHtml(str) {
  return String(str).replace(/[&<>'"]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s]));
}

function firstValidationMessage(j) {
  if (j?.message) return j.message;
  const e = j?.errors;
  if (!e) return 'Validasi gagal';
  const k = Object.keys(e)[0];
  return e[k]?.[0] ?? 'Validasi gagal';
}
</script>
