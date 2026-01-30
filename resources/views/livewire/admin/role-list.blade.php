<div class="location-list-page" style="min-height: 100vh;">
    <div class="page-wrap">
        
        {{-- Page Header --}}
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 600; color: var(--text); margin: 0 0 4px 0; line-height: 1.3;">
                Manajemen Role
            </h1>
            <p style="font-size: 14px; color: var(--muted); margin: 0;">
                Kelola role dan hak akses sistem
            </p>
        </div>

        {{-- Filter Section --}}
        <div style="background: var(--surface); border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
            <div class="grid-responsive-admin" style="grid-template-columns: 1fr auto;">
                
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px;">
                        Pencarian
                    </label>
                    <div style="position: relative;">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="location-list-input"
                            style="width: 100%; height: 40px; padding: 0 14px 0 40px; font-size: 14px; 
                                   border: 1px solid var(--border); border-radius: 10px; 
                                   background: var(--surface-2); color: var(--text);
                                   outline: none; transition: all 0.15s ease;"
                            placeholder="Cari role...">
                        <svg style="position: absolute; left: 14px; top: 12px; width: 16px; height: 16px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- New Role Button --}}
                <div style="display: flex; align-items: flex-end;">
                    <button wire:click="create"
                        style="height: 40px; padding: 0 20px; background: var(--primary); border: none; border-radius: 10px; 
                               color: white; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; white-space: nowrap; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);"
                        onmouseover="this.style.background='#1e40af'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Role
                    </button>
                </div>
            </div>
        </div>

        {{-- Stats Cards (Role Summary) --}}
        <div class="grid-responsive-stats-2" style="margin-bottom: 20px;">
            {{-- Total Role --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">Total Role</p>
                        <p style="font-size: 28px; font-weight: 600; color: var(--text); margin: 0;">{{ $roles->total() }}</p>
                    </div>
                    <div style="width: 44px; height: 44px; background: var(--primary-soft); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 22px; height: 22px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Permission Summary --}}
            <div style="background: var(--surface); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; justify-content: start; gap: 20px; overflow-x: auto;">
                    @php
                        $permissionGroups = [
                            'view' => ['label' => 'View', 'count' => 0],
                            'create' => ['label' => 'Create', 'count' => 0],
                            'edit' => ['label' => 'Edit', 'count' => 0],
                            'delete' => ['label' => 'Delete', 'count' => 0],
                        ];
                        foreach($allPermissions ?? [] as $perm) {
                            foreach(array_keys($permissionGroups) as $key) {
                                if(str_contains($perm->name, $key)) {
                                    $permissionGroups[$key]['count']++;
                                }
                            }
                        }
                    @endphp
                    @foreach($permissionGroups as $group)
                        <div>
                            <p style="font-size: 12px; font-weight: 500; color: var(--muted); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $group['label'] }}</p>
                            <p style="font-size: 18px; font-weight: 600; color: var(--text); margin: 0;">{{ $group['count'] }}</p>
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
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Role</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Permissions</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Jumlah User</th>
                            <th style="padding: 12px 24px; text-align: right; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="background: var(--surface);">
                        @forelse($roles as $role)
                            @if($role->name === 'super-admin')
                                @continue
                            @endif
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.15s;" 
                                onmouseover="this.style.background='var(--surface-2)'" 
                                onmouseout="this.style.background='var(--surface)'">
                                <td style="padding: 16px 24px;">
                                    <div style="display: flex; align-items: center;">
                                        @php
                                            $avatarBg = match($role->name) {
                                                'super-admin' => 'linear-gradient(135deg, #7c3aed, #a78bfa)',
                                                'admin' => 'linear-gradient(135deg, #2563eb, #60a5fa)',
                                                default => 'linear-gradient(135deg, #6366f1, #8b5cf6)',
                                            };
                                            $badgeBg = match($role->name) {
                                                'super-admin' => 'rgba(124, 58, 237, 0.1)',
                                                'admin' => 'rgba(37, 99, 235, 0.1)',
                                                default => 'rgba(107, 114, 128, 0.1)',
                                            };
                                            $badgeColor = match($role->name) {
                                                'super-admin' => '#7c3aed',
                                                'admin' => '#2563eb',
                                                default => '#4b5563',
                                            };
                                            $badgeBorder = match($role->name) {
                                                'super-admin' => 'rgba(124, 58, 237, 0.2)',
                                                'admin' => 'rgba(37, 99, 235, 0.2)',
                                                default => 'rgba(107, 114, 128, 0.2)',
                                            };
                                        @endphp
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $avatarBg }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);">
                                            {{ strtoupper(substr($role->name, 0, 1)) }}
                                        </div>
                                        <div style="margin-left: 12px;">
                                            <div style="font-size: 14px; font-weight: 600; color: var(--text);">{{ ucfirst($role->name) }}</div>
                                            <div style="font-size: 12px; color: var(--muted);">{{ $role->permissions->count() }} permissions</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($role->permissions->take(4) as $perm)
                                            <span style="font-size: 11px; background: var(--surface-2); border: 1px solid var(--border); padding: 2px 8px; border-radius: 6px; color: var(--text);">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                        @if($role->permissions->count() > 4)
                                            <span style="font-size: 11px; color: var(--muted); padding: 2px 4px;">+{{ $role->permissions->count() - 4 }} lainnya</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <div style="font-size: 14px; color: var(--text);">{{ $role->users()->count() }}</div>
                                    <div style="font-size: 12px; color: var(--muted);">pengguna</div>
                                </td>
                                <td style="padding: 16px 24px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        <button wire:click="edit({{ $role->id }})" 
                                            class="btn-touch"
                                            style="color: var(--primary);"
                                            onmouseover="this.style.background='var(--primary-soft)'"
                                            onmouseout="this.style.background='transparent'"
                                            title="Edit">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        @if($role->name !== 'super-admin')
                                            <button wire:click="confirmDelete({{ $role->id }}, '{{ $role->name }}')" 
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
                                        <svg style="width: 48px; height: 48px; margin-bottom: 12px; color: var(--border);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        <span style="font-size: 15px; font-weight: 500;">Tidak ada role ditemukan</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--surface);">
                {{ $roles->links() }}
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="show-on-mobile">
            @forelse($roles as $role)
                @if($role->name === 'super-admin')
                    @continue
                @endif
                <div class="mobile-card-row">
                    {{-- Role Header --}}
                    <div style="display: flex; align-items: center; margin-bottom: 12px;">
                        @php
                            $avatarBg = match($role->name) {
                                'admin' => 'linear-gradient(135deg, #2563eb, #60a5fa)',
                                default => 'linear-gradient(135deg, #6366f1, #8b5cf6)',
                            };
                        @endphp
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $avatarBg }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                            {{ strtoupper(substr($role->name, 0, 1)) }}
                        </div>
                        <div style="margin-left: 12px; flex: 1;">
                            <div style="font-size: 15px; font-weight: 600; color: var(--text);">{{ ucfirst($role->name) }}</div>
                            <div style="font-size: 13px; color: var(--muted);">{{ $role->permissions->count() }} permissions</div>
                        </div>
                    </div>

                    {{-- Permissions Preview --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                        @foreach($role->permissions->take(3) as $perm)
                            <span style="font-size: 10px; background: var(--surface-2); border: 1px solid var(--border); padding: 2px 8px; border-radius: 6px; color: var(--text);">
                                {{ $perm->name }}
                            </span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <span style="font-size: 10px; color: var(--muted); padding: 2px 4px;">+{{ $role->permissions->count() - 3 }}</span>
                        @endif
                    </div>

                    {{-- User Count --}}
                    <div style="font-size: 13px; color: var(--muted); margin-bottom: 12px;">
                        {{ $role->users()->count() }} pengguna
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid var(--border);">
                        <button wire:click="edit({{ $role->id }})" class="btn-touch" style="flex: 1; background: var(--primary-soft); color: var(--primary); font-size: 12px; font-weight: 500; gap: 6px;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </button>
                        @if($role->name !== 'super-admin')
                            <button wire:click="confirmDelete({{ $role->id }}, '{{ $role->name }}')" class="btn-touch" style="background: var(--danger-soft); color: var(--danger); font-size: 12px; font-weight: 500;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding: 40px 16px; text-align: center; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <p style="font-size: 14px; color: var(--muted); margin: 0;">Tidak ada role ditemukan</p>
                </div>
            @endforelse

            {{-- Mobile Pagination --}}
            <div style="margin-top: 16px; padding: 16px; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal (Mobile Safe) --}}
    @if($isModalOpen)
    <div style="position: fixed; z-index: 50; inset: 0;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px);" wire:click="$set('isModalOpen', false)"></div>
        
        <div style="position: relative; z-index: 10; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 16px;">
            <div class="modal-mobile-safe" style="position: relative; background: var(--surface); width: 100%; max-width: 500px; border-radius: 12px; box-shadow: var(--shadow-md); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
                
                <div style="padding: 16px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 10px;">
                        <span style="width: 32px; height: 32px; background: var(--primary-soft); color: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </span>
                        {{ $roleId ? 'Edit Role & Permissions' : 'Tambah Role Baru' }}
                    </h3>
                    <button wire:click="$set('isModalOpen', false)" style="background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer;">&times;</button>
                </div>
                
                <div style="padding: 24px; overflow-y: auto;">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 8px;">Nama Role</label>
                        <input wire:model="name" type="text" 
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text); font-size: 14px; outline: none; transition: border 0.15s;"
                            onfocus="this.style.borderColor='var(--primary)'"
                            onblur="this.style.borderColor='var(--border)'">
                        @error('name') <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 12px;">Permissions</label>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            @foreach($allPermissions as $perm)
                                <label style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; border-radius: 10px; border: 1px solid var(--border); cursor: pointer; transition: all 0.2s; background: var(--surface-2); position: relative;" 
                                       onmouseover="this.style.borderColor='var(--primary)'; this.style.background='var(--surface-3)'" 
                                       onmouseout="this.style.background=this.querySelector('input').checked ? 'var(--primary-soft)' : 'var(--surface-2)'; this.style.borderColor=this.querySelector('input').checked ? 'var(--primary)' : 'var(--border)'">
                                    
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}" 
                                        style="margin-top: 3px; width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary);"
                                        onchange="this.parentElement.style.background = this.checked ? 'var(--primary-soft)' : 'var(--surface-2)'; this.parentElement.style.borderColor = this.checked ? 'var(--primary)' : 'var(--border)'">
                                    
                                    <div style="flex: 1;">
                                        <span style="display: block; font-size: 14px; font-weight: 600; color: var(--text);">
                                            {{ $this->getPermissionLabel($perm->name) }}
                                        </span>
                                        <span style="display: block; font-size: 11px; color: var(--muted); margin-top: 2px;">
                                            Code: {{ $perm->name }}
                                        </span>
                                    </div>

                                    <!-- Active Indicator Border (Visual polish) -->
                                    <div x-show="$wire.selectedPermissions.includes('{{ $perm->name }}')" 
                                         style="position: absolute; inset: 0; border: 2px solid var(--primary); border-radius: 10px; pointer-events: none;"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--surface-2); display: flex; justify-content: flex-end; gap: 10px;">
                    <button wire:click="$set('isModalOpen', false)" class="btn btn-secondary"
                        style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.background='var(--surface-2)'; this.style.borderColor='var(--text)'"
                        onmouseout="this.style.background='var(--surface)'; this.style.borderColor='var(--border)'">
                        Batal
                    </button>
                    <button wire:click="confirmStore" class="btn btn-primary"
                        style="padding: 8px 16px; border-radius: 8px; border: none; background: var(--primary); color: white; cursor: pointer; transition: all 0.15s; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);"
                        onmouseover="this.style.background='#1e40af'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>
