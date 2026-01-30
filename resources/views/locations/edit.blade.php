<x-app-layout>
    <div class="location-list-page" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 1000px;">
            
            {{-- Page Header --}}
            <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1 style="font-size: 22px; font-weight: 600; color: var(--text); margin: 0 0 4px 0; line-height: 1.3;">
                        Edit Lokasi
                    </h1>
                    <p style="font-size: 14px; color: var(--muted); margin: 0;">
                        Perbarui informasi data lokasi
                    </p>
                </div>
                <div style="font-size: 12px; font-weight: 600; color: var(--muted); background: var(--surface-2); padding: 4px 12px; border-radius: 20px; border: 1px solid var(--border);">
                    ID: {{ $location->id }}
                </div>
            </div>

            <div style="background: var(--surface); border-radius: 12px; padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                {{-- Form Header --}}
                <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px;">
                    <h3 style="font-size: 18px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 10px;">
                        <span style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.1); color: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                             <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </span>
                        Form Edit Data Lokasi
                    </h3>
                    <p style="font-size: 13px; color: var(--muted); margin: 4px 0 0 42px;">Perbarui data dengan valid</p>
                </div>

                <form id="formLocation" method="POST" action="{{ route('locations.update', $location->id) }}" style="display: flex; flex-direction: column; gap: 24px;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="source" value="{{ $source ?? 'locations' }}">

                    {{-- 1. Identitas --}}
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <span style="width: 20px; height: 2px; background: #10b981;"></span> Identitas
                        </h4>
                        <div class="grid-responsive-form">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">NAMA PEMILIK</label>
                                <input type="text" name="nama" value="{{ old('nama', $location->nama) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="Nama lengkap">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">NOP</label>
                                <input type="text" name="nop" value="{{ old('nop', $location->nop) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="Nomor Objek Pajak">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">KODE DESA</label>
                                <input type="text" name="kode_desa" value="{{ old('kode_desa', $location->kode_desa) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="Contoh: 72.10.140.010">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">SHAPE</label>
                                <input type="text" name="shape" value="{{ old('shape', $location->shape) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="Point / Polygon">
                            </div>
                        </div>
                    </div>

                    {{-- 2. Properti --}}
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                             <span style="width: 20px; height: 2px; background: #10b981;"></span> Properti
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">LUAS (m²)</label>
                                <input type="number" step="0.01" name="luas" value="{{ old('luas', $location->luas) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">LUAS BANGUNAN (m²)</label>
                                <input type="number" step="0.01" name="luas_bangu" value="{{ old('luas_bangu', $location->luas_bangu) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="0.00">
                            </div>
                             <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">SERTIFIKAT</label>
                                <input type="text" name="sertpikat" value="{{ old('sertpikat', $location->sertpikat) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="Nomor sertifikat">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">NJOP (Rp)</label>
                                <input type="number" step="1" name="njop" value="{{ old('njop', $location->njop) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="0">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">USER PERUM</label>
                                <input type="text" name="user_perum" value="{{ old('user_perum', $location->user_perum) }}" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="Nama perumahan">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Koordinat --}}
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                             <span style="width: 20px; height: 2px; background: #10b981;"></span> Koordinat
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">LATITUDE <span style="color: var(--danger);">*</span></label>
                                <input type="text" name="latitude" value="{{ old('latitude', $location->latitude) }}" required
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="-0.xxxxxx">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px;">LONGITUDE <span style="color: var(--danger);">*</span></label>
                                <input type="text" name="longitude" value="{{ old('longitude', $location->longitude) }}" required
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'"
                                    placeholder="119.xxxxxx">
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="border-top: 1px solid var(--border); padding-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="{{ ($source ?? 'locations') === 'map' ? route('map') : route('locations.index') }}" 
                            style="padding: 10px 24px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 14px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.15s;"
                            onmouseover="this.style.background='var(--surface-2)'; this.style.borderColor='var(--text)'"
                            onmouseout="this.style.background='var(--surface)'; this.style.borderColor='var(--border)'">
                            Batal
                        </a>
                        <button type="submit" 
                            style="padding: 10px 24px; border-radius: 8px; border: none; background: #10b981; color: white; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.15s;"
                            onmouseover="this.style.background='#059669'"
                            onmouseout="this.style.background='#10b981'">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Update Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SweetAlert Logic --}}
    <script>
        document.getElementById('formLocation').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = this;
            
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Data lokasi akan diperbarui.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>