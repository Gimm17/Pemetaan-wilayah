<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public bool $showPassword = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('map', absolute: false), navigate: true);
    }
}; ?>

<div class="login-card">
    <!-- Icon -->
    <div class="login-icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </div>

    <!-- Title -->
    <h1 class="login-title">Masuk ke Sistem</h1>
    <p class="login-subtitle">Sistem Informasi Geografis<br>Pemetaan Wilayah</p>

    <!-- Session Status -->
    @if (session('status'))
        <div style="background: #dcfce7; color: #16a34a; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; text-align: center;">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">
        <!-- Email Address -->
        <div class="form-group">
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <input wire:model="form.email" 
                    id="email" 
                    class="form-input" 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    required 
                    autofocus 
                    autocomplete="username">
            </div>
            @error('form.email')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group" x-data="{ show: false }">
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <input wire:model="form.password" 
                    id="password" 
                    class="form-input" 
                    :type="show ? 'text' : 'password'"
                    name="password" 
                    placeholder="Password"
                    required 
                    autocomplete="current-password"
                    style="padding-right: 48px;">
                <button type="button" class="password-toggle" @click="show = !show">
                    <svg x-show="!show" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('form.password')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me Only -->
        <div style="margin-bottom: 24px;">
            <label class="remember-label">
                <input wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    class="remember-checkbox"
                    name="remember">
                <span>Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn" wire:loading.attr="disabled" wire:loading.class="loading">
            <span wire:loading.remove wire:target="login">Masuk</span>
            <span wire:loading.flex wire:target="login" class="loading-content">
                <svg class="spinner" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            </span>
        </button>
    </form>
</div>

