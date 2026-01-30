<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Title('Manajemen Role')]
class RoleList extends Component
{
    use WithPagination;

    public function getPermissionLabel($name)
    {
        $labels = [
            'admin.users.manage' => 'Kelola User (Admin)',
            'locations.view'     => 'Lihat Data Lokasi',
            'locations.create'   => 'Input Lokasi Baru',
            'locations.edit'     => 'Edit Data Lokasi',
            'locations.delete'   => 'Hapus Data Lokasi',
            'locations.delete_all' => 'Hapus SEMUA Data (Bulk Delete)',
            'locations.approve'  => 'Verifikasi / Approve Lokasi',
            'locations.submit'   => 'Ajukan Lokasi (Submit)',
            'exports.run'        => 'Export Data (Excel)',
            'imports.create'     => 'Import Data (Excel)',
        ];

        return $labels[$name] ?? ucwords(str_replace('.', ' ', $name));
    }

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    
    // Form Data
    public $roleId;
    public $name;
    public $selectedPermissions = [];
    
    // Delete State
    public $deleteId;
    public $deleteName;

    protected $rules = [
        'name' => 'required|min:3|unique:roles,name',
        'selectedPermissions' => 'array'
    ];

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->with('permissions')
            ->orderBy('name')
            ->paginate(10);
            
        $allPermissions = Permission::orderBy('name')->get();

        return view('livewire.admin.role-list', [
            'roles' => $roles,
            'allPermissions' => $allPermissions
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset(['roleId', 'name', 'selectedPermissions']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isModalOpen = true;
    }

    public function confirmStore()
    {
        $this->validate([
            'name' => 'required|min:3|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array'
        ]);

        $action = $this->roleId ? 'Mengupdate' : 'Menambahkan';
        $item = $this->roleId ? 'perubahan role' : 'role baru';

        $this->dispatch('swal:confirm', [
            'icon' => 'question',
            'title' => 'Simpan Data?',
            'text' => "Apakah Anda yakin ingin menyimpan {$item} ini?",
            'confirmButtonText' => 'Ya, Simpan',
            'method' => 'store',
            'params' => []
        ]);
    }

    #[\Livewire\Attributes\On('store')]
    public function store()
    {
        // Re-validate for security (optional but good practice)
        $this->validate([
            'name' => 'required|min:3|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array'
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
            $message = 'Role berhasil diperbarui';
        } else {
            $role = Role::create(['name' => $this->name]);
            $message = 'Role berhasil ditambahkan';
        }

        $role->syncPermissions($this->selectedPermissions);

        $this->isModalOpen = false;
        $this->dispatch('swal:toast', type: 'success', message: $message);
    }

    public function confirmDelete($id, $name)
    {
        $this->dispatch('swal:confirm', [
            'icon' => 'warning',
            'title' => 'Hapus Role?',
            'text' => "Role {$name} akan dihapus permanen. User dengan role ini mungkin akan kehilangan akses.",
            'confirmButtonText' => 'Ya, Hapus',
            'method' => 'delete',
            'params' => $id
        ]);
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting super-admin
        if ($role->name === 'super-admin') {
            $this->dispatch('swal:toast', type: 'error', message: 'Role Super Admin tidak bisa dihapus!');
            return;
        }

        $role->delete();
        $this->dispatch('swal:toast', type: 'success', message: 'Role berhasil dihapus');
    }
}
