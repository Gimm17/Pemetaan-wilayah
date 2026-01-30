<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Scoped to guest-page only - won't affect main app */
            .guest-page {
                --primary: #0ea5e9;
                --primary-hover: #0284c7;
                --primary-soft: rgba(14, 165, 233, 0.1);
                --text: #1e293b;
                --text-secondary: #64748b;
                --muted: #94a3b8;
                --border: #e2e8f0;
                --surface: #ffffff;
                --surface-2: #f8fafc;
                --danger: #ef4444;
            }

            .guest-page {
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                background: linear-gradient(180deg, 
                    #d4eeff 0%, 
                    #b8e4ff 20%, 
                    #9dd6f7 40%, 
                    #87ceeb 60%,
                    #b8e4ff 80%,
                    #e8f4fc 100%
                );
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                position: relative;
                overflow: hidden;
            }

            /* Cloud-like decorative elements */
            .guest-page::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 200px;
                background: linear-gradient(to top, 
                    rgba(255,255,255,0.9) 0%, 
                    rgba(255,255,255,0.5) 50%,
                    transparent 100%
                );
                pointer-events: none;
            }

            .guest-page::after {
                content: '';
                position: absolute;
                top: 50px;
                right: 10%;
                width: 300px;
                height: 100px;
                background: rgba(255,255,255,0.4);
                border-radius: 100px;
                filter: blur(40px);
                pointer-events: none;
            }

            .guest-page .login-container {
                width: 100%;
                max-width: 420px;
                position: relative;
                z-index: 10;
            }

            .guest-page .login-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 24px;
                padding: 40px;
                box-shadow: 
                    0 4px 6px -1px rgba(0, 0, 0, 0.05),
                    0 10px 15px -3px rgba(0, 0, 0, 0.08),
                    0 20px 25px -5px rgba(0, 0, 0, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.8);
            }

            .guest-page .login-icon {
                width: 56px;
                height: 56px;
                background: var(--surface-2);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 24px;
                border: 1px solid var(--border);
            }

            .guest-page .login-icon svg {
                width: 28px;
                height: 28px;
                color: var(--text);
            }

            .guest-page .login-title {
                font-size: 22px;
                font-weight: 700;
                color: var(--text);
                text-align: center;
                margin-bottom: 8px;
            }

            .guest-page .login-subtitle {
                font-size: 14px;
                color: var(--text-secondary);
                text-align: center;
                margin-bottom: 32px;
                line-height: 1.5;
            }

            .guest-page .form-group {
                margin-bottom: 16px;
            }

            .guest-page .input-wrapper {
                position: relative;
            }

            .guest-page .input-icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                width: 18px;
                height: 18px;
                color: var(--muted);
                pointer-events: none;
            }

            .guest-page .form-input {
                width: 100%;
                height: 52px;
                padding: 0 16px 0 48px;
                font-size: 15px;
                font-family: inherit;
                color: var(--text);
                background: var(--surface-2);
                border: 1px solid var(--border);
                border-radius: 12px;
                outline: none;
                transition: all 0.2s ease;
                box-sizing: border-box;
            }

            .guest-page .form-input::placeholder {
                color: var(--muted);
            }

            .guest-page .form-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 4px var(--primary-soft);
                background: var(--surface);
            }

            .guest-page .password-toggle {
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                padding: 4px;
                cursor: pointer;
                color: var(--muted);
                transition: color 0.2s;
            }

            .guest-page .password-toggle:hover {
                color: var(--text-secondary);
            }

            .guest-page .remember-label {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                color: var(--text-secondary);
                cursor: pointer;
            }

            .guest-page .remember-checkbox {
                width: 18px;
                height: 18px;
                border: 2px solid var(--border);
                border-radius: 5px;
                accent-color: var(--primary);
                cursor: pointer;
            }

            .guest-page .submit-btn {
                width: 100%;
                height: 52px;
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                color: white;
                border: none;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                font-family: inherit;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .guest-page .submit-btn:hover {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3);
            }

            .guest-page .submit-btn:active {
                transform: translateY(0);
            }

            .guest-page .submit-btn:disabled {
                cursor: not-allowed;
                opacity: 0.8;
            }

            .guest-page .loading-content {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .guest-page .input-error {
                color: var(--danger);
                font-size: 13px;
                margin-top: 6px;
            }

            /* Loading spinner */
            .guest-page .spinner {
                animation: guest-spin 1s linear infinite;
            }

            @keyframes guest-spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            /* Mobile adjustments */
            @media (max-width: 480px) {
                .guest-page .login-card {
                    padding: 32px 24px;
                }

                .guest-page .login-title {
                    font-size: 20px;
                }
            }
        </style>
    </head>
    <body class="guest-page">
        <div class="login-container">
            {{ $slot }}
        </div>
    </body>
</html>
