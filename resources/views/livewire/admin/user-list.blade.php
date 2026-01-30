<div class="location-list-page" style="min-height: 100vh;">
    <!-- Trap for Browser Autofill -->
    <div style="opacity: 0; position: absolute; top: 0; left: 0; height: 0; width: 0; z-index: -1; overflow: hidden;">
        <input type="text" name="fake_username_prevention" autocomplete="username">
        <input type="password" name="fake_password_prevention" autocomplete="current-password">
    </div>

    <div class="page-wrap">
        
        {{-- Page Header --}}
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 600; color: var(--text); margin: 0 0 4px 0; line-height: 1.3;">
                Manajemen User
            </h1>
            <p style="font-size: 14px; color: var(--muted); margin: 0;">
                Kelola hak akses dan akun pengguna
            </p>
        </div>

        {{-- Filter Section --}}
        <div style="background: var(--surface); border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
            <div class="grid-responsive-admin">
                
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px;">
                        Pencarian
                    </label>
                    <div style="position: relative;">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            autocomplete="off"
                            class="location-list-input"
                            style="width: 100%; height: 40px; padding: 0 14px 0 40px; font-size: 14px; 
                                   border: 1px solid var(--border); border-radius: 10px; 
                                   background: var(--surface-2); color: var(--text);
                                   outline: none; transition: all 0.15s ease;"
                            placeholder="Cari user (nama, email)...">
                        <svg style="position: absolute; left: 14px; top: 12px; width: 16px; height: 16px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Role Filter --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px;">
                        Role
                    </label>
                    <select wire:model.live="roleFilter"
                        class="location-list-select"
                        style="width: 100%; height: 40px; padding: 0 14px; font-size: 14px; 
                               border: 1px solid var(--border); border-radius: 10px; 
                               background: var(--surface-2); color: var(--text);
                               outline: none; cursor: pointer; transition: all 0.15s ease;">
                        <option value="">Semua Role</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- New User Button --}}
                <div style="display: flex; align-items: flex-end;">
                    <button wire:click="openModal"
                        style="height: 40px; padding: 0 20px; background: var(--primary); border: none; border-radius: 10px; 
                               color: white; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; white-space: nowrap; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);"
                        onmouseover="this.style.background='#1e40af'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        User Baru
                    </button>
                </div>
            </div>
        </div>

        {{-- Stats Cards (User Summary) --}}
        <div class="grid-responsive-stats-2" style="margin-bottom: 20px;">
             {{-- Total Data --}}
             <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Total User</p>
                        <p style="font-size: 28px; font-weight: 600; color: var(--text); margin: 0;">{{ $users->total() }}</p>
                    </div>
                    <div style="width: 44px; height: 44px; background: var(--primary-soft); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 22px; height: 22px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- Role Summary (Just Example) --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: start; gap: 20px; overflow-x: auto;">
                    @foreach($roles->take(3) as $r)
                        <div>
                             <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ ucfirst($r->name) }}</p>
                             <p style="font-size: 18px; font-weight: 600; color: var(--text); margin: 0;">{{ $r->users()->count() }}</p>
                        </div>
                        @if(!$loop->last)
                            <div style="width: 1px; height: 30px; background: var(--border);"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Table Section (Desktop) --}}
        <div class="hide-on-mobile" style="background: var(--surface); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--surface-2); border-bottom: 1px solid var(--border);">
                        <tr>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">User</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Role</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Daftar</th>
                            <th style="padding: 12px 24px; text-align: right; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="background: var(--surface);">
                        @forelse($users as $user)
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.15s;" 
                                onmouseover="this.style.background='var(--surface-2)'" 
                                onmouseout="this.style.background='var(--surface)'">
                                <td style="padding: 16px 24px;">
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div style="margin-left: 12px;">
                                            <div style="font-size: 14px; font-weight: 600; color: var(--text);">{{ $user->name }}</div>
                                            <div style="font-size: 12px; color: var(--muted);">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px 24px;">
                                    @php
                                        $roleName = $user->roles->first()->name ?? 'user';
                                        $badgeBg = match($roleName) {
                                            'super-admin' => 'rgba(124, 58, 237, 0.1)',
                                            'admin' => 'rgba(37, 99, 235, 0.1)',
                                            default => 'rgba(107, 114, 128, 0.1)',
                                        };
                                        $badgeColor = match($roleName) {
                                            'super-admin' => '#7c3aed', // Purple
                                            'admin' => '#2563eb', // Blue
                                            default => '#4b5563', // Gray
                                        };
                                        $badgeBorder = match($roleName) {
                                            'super-admin' => 'rgba(124, 58, 237, 0.2)',
                                            'admin' => 'rgba(37, 99, 235, 0.2)',
                                            default => 'rgba(107, 114, 128, 0.2)',
                                        };
                                    @endphp
                                    <span style="display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 500; background: {{ $badgeBg }}; color: {{ $badgeColor }}; border: 1px solid {{ $badgeBorder }};">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <div style="font-size: 14px; color: var(--text);">{{ $user->created_at->format('d M Y') }}</div>
                                    <div style="font-size: 12px; color: var(--muted);">{{ $user->created_at->format('H:i') }}</div>
                                </td>
                                <td style="padding: 16px 24px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        <button wire:click="edit({{ $user->id }})" 
                                            class="btn-touch"
                                            style="color: var(--primary);"
                                            onmouseover="this.style.background='var(--primary-soft)'"
                                            onmouseout="this.style.background='transparent'"
                                            title="Edit">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button wire:click="confirmDelete({{ $user->id }})" 
                                                class="btn-touch"
                                                style="color: var(--danger);"
                                                onmouseover="this.style.background='var(--danger-soft)'"
                                                onmouseout="this.style.background='transparent'"
                                                title="Hapus">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 40px; text-align: center;">
                                    <div style="display: flex; flex-direction: column; align-items: center; color: var(--muted);">
                                        <svg style="width: 48px; height: 48px; margin-bottom: 12px; color: var(--border);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span style="font-size: 15px; font-weight: 500;">Tidak ada user ditemukan</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--surface);">
                {{ $users->links() }}
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="show-on-mobile">
            @forelse($users as $user)
                <div class="mobile-card-row">
                    {{-- User Header --}}
                    <div style="display: flex; align-items: center; margin-bottom: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div style="margin-left: 12px; flex: 1;">
                            <div style="font-size: 15px; font-weight: 600; color: var(--text);">{{ $user->name }}</div>
                            <div style="font-size: 13px; color: var(--muted);">{{ $user->email }}</div>
                        </div>
                    </div>

                    {{-- Role & Date --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        @php
                            $roleName = $user->roles->first()->name ?? 'user';
                            $badgeBg = match($roleName) {
                                'super-admin' => 'rgba(124, 58, 237, 0.1)',
                                'admin' => 'rgba(37, 99, 235, 0.1)',
                                default => 'rgba(107, 114, 128, 0.1)',
                            };
                            $badgeColor = match($roleName) {
                                'super-admin' => '#7c3aed',
                                'admin' => '#2563eb',
                                default => '#4b5563',
                            };
                        @endphp
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 500; background: {{ $badgeBg }}; color: {{ $badgeColor }};">
                            {{ ucfirst($roleName) }}
                        </span>
                        <span style="font-size: 12px; color: var(--muted);">{{ $user->created_at->format('d M Y') }}</span>
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid var(--border);">
                        <button wire:click="edit({{ $user->id }})" class="btn-touch" style="flex: 1; background: var(--primary-soft); color: var(--primary); font-size: 12px; font-weight: 500; gap: 6px;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </button>
                        @if($user->id !== auth()->id())
                            <button wire:click="confirmDelete({{ $user->id }})" class="btn-touch" style="background: var(--danger-soft); color: var(--danger); font-size: 12px; font-weight: 500;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding: 40px 16px; text-align: center; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <p style="font-size: 14px; color: var(--muted); margin: 0;">Tidak ada user ditemukan</p>
                </div>
            @endforelse

            {{-- Mobile Pagination --}}
            <div style="margin-top: 16px; padding: 16px; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
                {{ $users->links() }}
            </div>
        </div>
    </div>


    {{-- Modal (Mobile Safe) --}}
    @if($isModalOpen)
    <div style="position: fixed; z-index: 50; inset: 0; overflow-y: auto;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 16px; text-align: center;">
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); transition: opacity 0.3s;" aria-hidden="true" wire:click="closeModal"></div>
            
            <div class="modal-mobile-safe" style="position: relative; background: var(--surface); border-radius: 12px; text-align: left; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transform: transition 0.3s; width: 100%; max-width: 500px;">
                <div style="padding: 24px;">
                    <h3 style="font-size: 18px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <span style="width: 32px; height: 32px; background: var(--primary-soft); color: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                             <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        {{ $isEditMode ? 'Edit User' : 'Tambah User' }}
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Nama Lengkap</label>
                            <input type="text" wire:model="name" 
                                style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                onfocus="this.style.borderColor='var(--primary)'"
                                onblur="this.style.borderColor='var(--border)'">
                            @error('name') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Email</label>
                            <input type="email" wire:model="email" 
                                style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                onfocus="this.style.borderColor='var(--primary)'"
                                onblur="this.style.borderColor='var(--border)'">
                            @error('email') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">Role</label>
                            @if($isEditMode && $role === 'super-admin')
                                {{-- Read-only display for super-admin --}}
                                <div style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; display: flex; align-items: center; opacity: 0.7; cursor: not-allowed;">
                                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #7c3aed;"></span>
                                        Super-admin
                                        <span style="font-size: 11px; color: var(--muted); margin-left: 8px;">🔒 Tidak dapat diubah</span>
                                    </span>
                                </div>
                                <input type="hidden" wire:model="role" value="super-admin">
                            @else
                                <select wire:model="role" 
                                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; cursor: pointer;"
                                    onfocus="this.style.borderColor='var(--primary)'"
                                    onblur="this.style.borderColor='var(--border)'">
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $r)
                                        {{-- Tampilkan super-admin hanya jika user yang login juga super-admin --}}
                                        @if($r->name !== 'super-admin' || auth()->user()->hasRole('super-admin'))
                                            <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                            @error('role') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;">
                                Password
                                @if($isEditMode) <span style="font-weight: 400; text-transform: none; margin-left: 4px;">(Opsional)</span> @endif
                            </label>
                            <div style="position: relative;">
                                <input :type="show ? 'text' : 'password'" wire:model="password" 
                                    style="width: 100%; height: 40px; padding: 0 40px 0 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                                    onfocus="this.style.borderColor='var(--primary)'"
                                    onblur="this.style.borderColor='var(--border)'">
                                <button type="button" @click="show = !show" 
                                    style="position: absolute; right: 0; top: 0; height: 100%; padding: 0 12px; background: transparent; border: none; cursor: pointer; color: var(--muted); display: flex; align-items: center;">
                                    <svg x-show="!show" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" style="display: none; width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                            @error('password') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div style="background: var(--surface-2); padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px;">
                    <button wire:click="closeModal" type="button" 
                        style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.background='var(--surface-2)'; this.style.borderColor='var(--text)'"
                        onmouseout="this.style.background='var(--surface)'; this.style.borderColor='var(--border)'">
                        Batal
                    </button>
                    <button wire:click="{{ $isEditMode ? 'confirmUpdate' : 'confirmStore' }}" type="button" 
                        style="padding: 8px 16px; border-radius: 8px; border: none; background: var(--primary); color: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);"
                        onmouseover="this.style.background='#1e40af'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">
                        {{ $isEditMode ? 'Simpan' : 'Tambah' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
