<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
#[Title('Manajemen User')]
class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    public $isModalOpen = false;
    public $isEditMode = false;
    public $userIdBeingEdited = null;

    // Form inputs
    public $name;
    public $email;
    public $password;
    public $role;

    protected $listeners = ['refreshUsers' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->with('roles')->latest()->paginate(10);
        $roles = Role::all();

        return view('livewire.admin.user-list', [
            'users' => $users,
            'roles' => $roles,
            'allPermissions' => \Spatie\Permission\Models\Permission::orderBy('name')->get()
        ]);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'role', 'userIdBeingEdited', 'isEditMode']);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function confirmStore()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $this->dispatch('swal:confirm', [
            'title' => 'Tambah User?', 
            'text' => 'User baru akan ditambahkan ke sistem.', 
            'icon' => 'question', 
            'method' => 'store', 
            'params' => []
        ]);
    }

    #[\Livewire\Attributes\On('store')]
    public function store()
    {
        // Validation is already done in confirmStore, but good to keep or trust the flow.
        // Re-validation avoids hacked requests skipping text check, but strictly redundant for UX.
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        $this->closeModal();
        $this->dispatch('swal:toast', type: 'success', message: 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userIdBeingEdited = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()->name ?? '';
        $this->password = ''; // Don't persist password field on edit
        
        $this->isEditMode = true;
        $this->isModalOpen = true;
        $this->resetValidation();
    }

    public function confirmUpdate()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userIdBeingEdited,
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6', // Optional on edit
        ]);

        $this->dispatch('swal:confirm', [
            'title' => 'Simpan Perubahan?', 
            'text' => 'Data user akan diperbarui.', 
            'icon' => 'question', 
            'method' => 'update', 
            'params' => []
        ]);
    }

    #[\Livewire\Attributes\On('update')]
    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userIdBeingEdited,
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6', // Optional on edit
        ]);

        $user = User::findOrFail($this->userIdBeingEdited);
        
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $user->syncRoles([$this->role]);

        $this->closeModal();
        $this->dispatch('swal:toast', type: 'success', message: 'User berhasil diperbarui.');
    }

    public function confirmDelete($id)
    {
        if ($id === \Illuminate\Support\Facades\Auth::id()) {
            $this->dispatch('swal:toast', type: 'error', message: 'Anda tidak dapat menghapus akun sendiri!');
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => 'Hapus User?', 
            'text' => 'User ini akan dihapus permanen.', 
            'icon' => 'warning', 
            'method' => 'deleteUser', 
            'params' => $id
        ]);
    }

    #[\Livewire\Attributes\On('deleteUser')]
    public function deleteUser($id)
    {
        if ($id === \Illuminate\Support\Facades\Auth::id()) return;
        
        $user = User::findOrFail($id);
        $user->delete();
        
        $this->dispatch('swal:toast', type: 'success', message: 'User berhasil dihapus.');
    }
}
