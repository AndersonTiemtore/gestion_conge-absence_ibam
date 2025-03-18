@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Connexion - e-CongeIBAM')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
    <style>
        .auth-cover-bg-color {
            background: linear-gradient(135deg, #6B46C1 0%, #3B82F6 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-cover-bg-color::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .auth-illustration {
            transform: scale(1);
            transition: transform 0.3s ease;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));
        }

        .auth-illustration:hover {
            transform: scale(1.05);
        }

        .authentication-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }

        .form-control:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #6B46C1 0%, #3B82F6 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(107, 70, 193, 0.3);
        }

        .welcome-text {
            font-size: 2rem;
            background: linear-gradient(135deg, #6B46C1 0%, #3B82F6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }

        .form-check-input:checked {
            background-color: #6B46C1;
            border-color: #6B46C1;
        }

        .app-logo {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
        
        .app-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6B46C1 0%, rgb(255, 115, 0) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="app-brand auth-cover-brand">
            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 40, 'withbg' => 'fill: #6B46C1;'])</span>
            <span class="app-brand-text demo text-heading fw-bold">e-CongeIBAM</span>
        </a>
        <!-- /Logo -->

        <div class="authentication-inner row m-0">
            <!-- Left Text -->
            <div class="d-none d-lg-flex col-lg-8 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/illustrations/auth-login-illustration-' . $configData['style'] . '.png') }}"
                        alt="auth-login-cover" class="my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-login-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png">

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-' . $configData['style'] . '.png') }}"
                        alt="auth-login-cover" class="platform-bg"
                        data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 pt-5">
                    <h1 class="welcome-text">Bienvenue sur e-CongeIBAM! 👋</h1>
                    <p class="mb-6 text-muted">Votre portail personnel de gestion des congés</p>

                    <form id="formAuthentication" class="mb-6" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="ti ti-mail text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                    id="email" name="email" placeholder="prenom.nom@ibam.com" 
                                    value="{{ old('email') }}" autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Mot de passe</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="ti ti-lock text-muted"></i>
                                </span>
                                <input type="password" id="password"
                                    class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                    name="password"
                                    placeholder="Entrez votre mot de passe"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer border-start-0">
                                    <i class="ti ti-eye-off"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="my-8">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                    <label class="form-check-label" for="remember-me">
                                        Se souvenir de moi
                                    </label>
                                </div>
                                <a href="{{ route('password.forgotten') }}" class="text-primary">
                                    Mot de passe oublié ?
                                </a>
                            </div>
                        </div>

                        <button class="btn btn-primary d-grid w-100">
                            <i class="ti ti-login me-2"></i> Se connecter
                        </button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection