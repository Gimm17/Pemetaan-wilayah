<div class="location-list-page" style="min-height: 100vh;">
    <div class="page-wrap">
        
        {{-- Page Header --}}
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 600; color: var(--text); margin: 0 0 4px 0; line-height: 1.3;">
                Profil Saya
            </h1>
            <p style="font-size: 14px; color: var(--muted); margin: 0;">
                Kelola informasi akun dan keamanan Anda
            </p>
        </div>

        <div class="grid-responsive-stats-2">
            
            {{-- Profile Card --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; background: var(--primary-soft); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 style="font-size: 16px; font-weight: 600; color: var(--text); margin: 0;">Informasi Dasar</h2>
                        <p style="font-size: 12px; color: var(--muted); margin: 0;">Nama dan kontak email Anda</p>
                    </div>
                </div>

                <form wire:submit.prevent="confirmUpdateProfile" style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Nama Lengkap</label>
                        <input type="text" wire:model="name" 
                            style="width: 100%; height: 42px; padding: 0 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                            onfocus="this.style.borderColor='var(--primary)'"
                            onblur="this.style.borderColor='var(--border)'">
                        @error('name') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Email</label>
                        <input type="email" wire:model="email" 
                            style="width: 100%; height: 42px; padding: 0 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                            onfocus="this.style.borderColor='var(--primary)'"
                            onblur="this.style.borderColor='var(--border)'">
                        @error('email') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-top: 8px; text-align: right;">
                        <button type="submit" 
                            style="padding: 10px 24px; border-radius: 8px; border: none; background: var(--primary); color: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);"
                            onmouseover="this.style.background='#1e40af'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            {{-- Security Card --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                 <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; background: rgba(16, 185, 129, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                         <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h2 style="font-size: 16px; font-weight: 600; color: var(--text); margin: 0;">Keamanan</h2>
                        <p style="font-size: 12px; color: var(--muted); margin: 0;">Update password akun Anda</p>
                    </div>
                </div>

                <form wire:submit.prevent="confirmUpdatePassword" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    <div x-data="{ show: false }">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Password Saat Ini</label>
                        <div style="position: relative;">
                            <input :type="show ? 'text' : 'password'" wire:model="current_password" 
                                style="width: 100%; height: 42px; padding: 0 40px 0 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                onfocus="this.style.borderColor='#10b981'"
                                onblur="this.style.borderColor='var(--border)'">
                            <button type="button" @click="show = !show" 
                                style="position: absolute; right: 0; top: 0; height: 100%; padding: 0 12px; background: transparent; border: none; cursor: pointer; color: var(--muted); display: flex; align-items: center;">
                                <svg x-show="!show" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" style="display: none; width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        @error('current_password') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div x-data="{ show: false }">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Password Baru</label>
                            <div style="position: relative;">
                                <input :type="show ? 'text' : 'password'" wire:model="password" 
                                    style="width: 100%; height: 42px; padding: 0 40px 0 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'">
                                <button type="button" @click="show = !show" 
                                    style="position: absolute; right: 0; top: 0; height: 100%; padding: 0 12px; background: transparent; border: none; cursor: pointer; color: var(--muted); display: flex; align-items: center;">
                                    <svg x-show="!show" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" style="display: none; width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                            @error('password') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>

                         <div x-data="{ show: false }">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Konfirmasi</label>
                            <div style="position: relative;">
                                <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" 
                                    style="width: 100%; height: 42px; padding: 0 40px 0 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='#10b981'"
                                    onblur="this.style.borderColor='var(--border)'">
                                <button type="button" @click="show = !show" 
                                    style="position: absolute; right: 0; top: 0; height: 100%; padding: 0 12px; background: transparent; border: none; cursor: pointer; color: var(--muted); display: flex; align-items: center;">
                                    <svg x-show="!show" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" style="display: none; width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 8px; text-align: right;">
                        <button type="submit" 
                            style="padding: 10px 24px; border-radius: 8px; border: none; background: #10b981; color: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s; display: inline-flex; align-items: center; gap: 8px;"
                            onmouseover="this.style.background='#059669'"
                            onmouseout="this.style.background='#10b981'">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger Zone: Delete All Data --}}
        @if(auth()->user()->can('locations.delete_all') || auth()->user()->hasRole('super-admin'))
        <div style="margin-top: 24px; background: var(--surface); border-radius: 12px; padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--danger-border, #fecaca);">
             <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; background: var(--danger-soft, #fee2e2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--danger);">
                     <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 600; color: var(--danger); margin: 0;">Zona Berbahaya</h2>
                    <p style="font-size: 12px; color: var(--muted); margin: 0;">Tindakan yang tidak dapat dibatalkan</p>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="padding: 12px; background: var(--danger-soft, #fef2f2); border-radius: 8px; border: 1px solid var(--danger-border, #fecaca); font-size: 13px; color: var(--danger); line-height: 1.5;">
                    <strong>Peringatan:</strong> Fitur ini akan menghapus <u>SEMUA</u> data lokasi yang ada di database. Data yang dihapus tidak dapat dipulihkan kembali. Gunakan dengan sangat hati-hati.
                </div>
                
                <div style="text-align: right;">
                    <button type="button" wire:click="confirmDeleteAllLocations"
                        style="padding: 10px 24px; border-radius: 8px; border: none; background: var(--danger); color: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s; display: inline-flex; align-items: center; gap: 8px;"
                        onmouseover="this.style.background='#b91c1c'"
                        onmouseout="this.style.background='var(--danger)'">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus SEMUA Data Lokasi
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
