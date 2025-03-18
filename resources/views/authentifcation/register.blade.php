@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Inscription')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/bs-stepper/bs-stepper.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/bs-stepper/bs-stepper.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/pages-auth-multisteps.js'])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="app-brand auth-cover-brand">
            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row">

            <!-- Left Text -->
            <div
                class="d-none d-lg-flex col-lg-4 align-items-center justify-content-center p-5 auth-cover-bg-color position-relative auth-multisteps-bg-height">
                <img src="{{ asset('assets/img/illustrations/auth-register-multisteps-illustration.png') }}"
                    alt="auth-register-multisteps" class="img-fluid" width="280">

                <img src="{{ asset('assets/img/illustrations/auth-register-multisteps-shape-' . $configData['style'] . '.png') }}"
                    alt="auth-register-multisteps" class="platform-bg"
                    data-app-light-img="illustrations/auth-register-multisteps-shape-light.png"
                    data-app-dark-img="illustrations/auth-register-multisteps-shape-dark.png">
            </div>
            <!-- /Left Text -->

            <!--  Multi Steps Registration -->
            <div class="d-flex col-lg-8 align-items-center justify-content-center authentication-bg p-5">
                <div class="w-px-700">
                    <div id="multiStepsValidation" class="bs-stepper border-none shadow-none mt-5">
                        <div class="bs-stepper-header border-none pt-12 px-0">
                            <div class="step" data-target="#accountDetailsValidation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="ti ti-file-analytics ti-md"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Compte</span>
                                        <span class="bs-stepper-subtitle">Détails du compte</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#personalInfoValidation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="ti ti-user ti-md"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Etablissement</span>
                                        <span class="bs-stepper-subtitle">Entrez les Information</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#billingLinksValidation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="ti ti-credit-card ti-md"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Conditions d'utilisation</span>
                                        {{-- <span class="bs-stepper-subtitle">Payment Details</span> --}}
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content px-0">
                            <form id="multiStepsForm" method="POST" onSubmit="return false">
                                @csrf
                                <!-- Account Details -->
                                <div id="accountDetailsValidation" class="content">
                                    <div class="content-header mb-6">
                                        <h4 class="mb-0">Information Utilisateur</h4>
                                        <p class="mb-0">Entrez les Détails du Compte</p>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="multiStepsFirstName">Nom</label>
                                            <input type="text" name="nom" id="multiStepsFirstName"
                                                class="form-control" placeholder="Doo" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="multiStepsLastName">Prénom</label>
                                            <input type="text" name="prenom" id="multiStepsLastName"
                                                class="form-control" placeholder="John" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="multiStepsEmail">Email</label>
                                            <input type="email" name="email" id="multiStepsEmail" class="form-control"
                                                placeholder="johndoe@email.com" aria-label="john.doe" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="multiStepsMobile">Téléphone</label>
                                            <input type="text" name="telephone" id="multiStepsMobile"
                                                class="form-control" placeholder="50505050" />
                                        </div>
                                        <div class="col-sm-6 form-password-toggle">
                                            <label class="form-label" for="multiStepsPass">Mot de passe</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="multiStepsPass" name="password"
                                                    class="form-control"
                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                    aria-describedby="multiStepsPass2" />
                                                <span class="input-group-text cursor-pointer" id="multiStepsPass2"><i
                                                        class="ti ti-eye-off"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 form-password-toggle">
                                            <label class="form-label" for="multiStepsConfirmPass">Confirmer le mot de
                                                passe</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="multiStepsConfirmPass"
                                                    name="password_confirmation" class="form-control"
                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                    aria-describedby="multiStepsConfirmPass2" />
                                                <span class="input-group-text cursor-pointer"
                                                    id="multiStepsConfirmPass2"><i class="ti ti-eye-off"></i></span>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-between">
                                            <button class="btn btn-label-secondary btn-prev" disabled> <i
                                                    class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Précédent</span>
                                            </button>
                                            <button class="btn btn-primary btn-next"> <span
                                                    class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Suivant</span>
                                                <i class="ti ti-arrow-right ti-xs"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Personal Info -->
                                <div id="personalInfoValidation" class="content">
                                    <div class="content-header mb-6">
                                        <h4 class="mb-0">Informations Etablissement</h4>
                                        <p class="mb-0">Entrez Les Détails</p>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="etablissement">Etablissement</label>
                                            <input type="text" id="etablissement" name="etablissement"
                                                class="form-control" />
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="form-label" for="logo">Logo</label>
                                            <input type="file" id="logo" name="logo"
                                                class="form-control multi-steps-pincode" accept="image/*" />
                                        </div>

                                        <div class="col-12 d-flex justify-content-between">
                                            <button class="btn btn-label-secondary btn-prev"> <i
                                                    class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Précédent</span>
                                            </button>
                                            <button class="btn btn-primary btn-next"> <span
                                                    class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Suivant</span>
                                                <i class="ti ti-arrow-right ti-xs"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Conditions -->
                                <div id="billingLinksValidation" class="content">
                                    <div class="content-header mb-6">
                                        <h4 class="mb-0">Conditions d'utilisation</h4>
                                        <p class="mb-0">Veuillez accepter les conditions d'utilisation pour continuer</p>
                                    </div>
                                    <!-- Custom plan options -->
                                    <div class="row gap-md-0 gap-4 mb-12">
                                        <div class="col-md">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="standardOption">
                                                    <span class="custom-option-body">
                                                        <span class="d-block mb-2 h5">Conditions d'utilisation</span>
                                                        <span>J'accepte les conditions d'utilisation mentionnées</span>
                                                    </span>
                                                    <input name="terms" class="form-check-input" type="checkbox"
                                                        id="standardOption" checked />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between mt-5">
                                            <button class="btn btn-label-secondary btn-prev">
                                                <i class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Précédent</span>
                                            </button>
                                            <button type="submit"
                                                class="btn btn-success btn-next btn-submit">S'inscrire</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Multi Steps Registration -->
        </div>
    </div>

    <script type="module">
        // Check selected custom option
        window.Helpers.initCustomOptionCheck();
    </script>
@endsection
