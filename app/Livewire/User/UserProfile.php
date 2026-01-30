<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
#[Title('Profil Saya')]
class UserProfile extends Component
{
    // Profile Data
    public $name;
    public $email;

    // Password Update
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function confirmUpdateProfile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $this->dispatch('swal:confirm', [
            'title' => 'Simpan Profil?', 
            'text' => 'Informasi profil Anda akan diperbarui.', 
            'icon' => 'question', 
            'method' => 'updateProfile', 
            'params' => []
        ]);
    }

    #[\Livewire\Attributes\On('updateProfile')]
    public function updateProfile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Profil berhasil diperbarui.');
    }

    public function confirmUpdatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:6|confirmed',
        ]);

        $this->dispatch('swal:confirm', [
            'title' => 'Ubah Password?', 
            'text' => 'Password Anda akan diubah. Pastikan Anda mengingat password baru.', 
            'icon' => 'warning', 
            'method' => 'updatePassword', 
            'params' => []
        ]);
    }

    #[\Livewire\Attributes\On('updatePassword')]
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        $this->dispatch('swal:toast', type: 'success', message: 'Password berhasil diubah.');
    }

    public function confirmDeleteAllLocations()
    {
        if (!auth()->user()->can('locations.delete_all') && !auth()->user()->hasRole('super-admin')) {
             $this->dispatch('swal:toast', type: 'error', message: 'Anda tidak memiliki izin (Butuh: locations.delete_all).');
             return;
        }

        $this->dispatch('swal:confirm', [
            'title' => 'Hapus SEMUA Data?', 
            'text' => 'PERINGATAN: Ini akan menghapus SELURUH data lokasi. Ketik "HAPUS" di bawah untuk konfirmasi.', 
            'icon' => 'warning', 
            'input' => 'text',
            'inputMatch' => 'HAPUS',
            'method' => 'deleteAllLocations', 
            'params' => [],
            'confirmButtonText' => 'Ya, Hapus Semua!',
            'confirmButtonColor' => '#ef4444'
        ]);
    }

    #[\Livewire\Attributes\On('deleteAllLocations')]
    public function deleteAllLocations()
    {
        if (!auth()->user()->can('locations.delete_all') && !auth()->user()->hasRole('super-admin')) {
             $this->dispatch('swal:toast', type: 'error', message: 'Akses ditolak.');
             return;
        }

        // Delete Logic
        try {
            \App\Models\Location::query()->delete();
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE locations AUTO_INCREMENT = 1;');
            
            $this->dispatch('swal:toast', type: 'success', message: 'Semua data lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('swal:toast', type: 'error', message: 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.user-profile');
    }
}
