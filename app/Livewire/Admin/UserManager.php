<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    public $email = '';
    public $name = '';
    public $password = '';
    public $role = 'viewer';

    public function create()
    {
        $this->validate([
            'email' => ['required','email'],
            'name' => ['required','string','max:255'],
            'password' => ['required','string','min:6'],
            'role' => ['required','string'],
        ]);

        $u = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        $r = Role::findByName($this->role);
        $u->assignRole($r);

        $this->reset(['email','name','password']);
        $this->dispatch('swal:toast', type: 'success', message: 'User dibuat');
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();
        $users = User::with('roles')->latest()->limit(50)->get();
        return view('livewire.admin.user-manager', compact('roles','users'));
    }
}
