@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutFront')

@section('title', 'Landing - Front Pages')

<!-- Vendor Styles -->
@section('vendor-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @vite(['resources/assets/vendor/libs/nouislider/nouislider.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/front-page-landing.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/nouislider/nouislider.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/front-page-landing.js'])
@endsection


@section('content')
    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="hero-animation">
            <div id="landingHero" class="section-py landing-hero position-relative">
                <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
                    class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100"
                    data-speed="1" />
                <div class="container">
                    <div class="hero-text-box text-center position-relative">
                        <h1 class="text-primary hero-title display-6 fw-extrabold">
                            Une plateforme unique pour gérer toutes vos demandes de congé
                        </h1>
                        <h2 class="hero-sub-title h6 mb-6">
                            Solution prête à l'emploi & facile à utiliser pour la gestion des congés et absences<br
                                class="d-none d-lg-block" />
                            Fiabilité et personnalisation garanties.
                        </h2>
                        {{-- <div class="landing-hero-btn d-inline-block position-relative mb-10">
                            <a href="#landingPricing" class="btn btn-primary btn-lg">Accédez à la démo</a>
                        </div> --}}
                    </div>

                    <div id="heroDashboardAnimation" class="hero-animation-img">
                        <a href="{{ url('/app/ecommerce/dashboard') }}" target="_blank">
                            <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                                <img src="{{ asset('assets/img/layouts/dashboard.png') }}" alt="hero dashboard"
                                    class="animation-img" data-app-light-img="layouts/dashboard.png"
                                    data-app-dark-img="layouts/dashboard.png" />
                                <img src="{{ asset('assets/img/layouts/dashboard.png') }}" alt="hero elements"
                                    class="position-absolute hero-elements-img animation-img top-0 start-0"
                                    data-app-light-img="layouts/dashboard.png" data-app-dark-img="layouts/dashboard.png" />
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="landing-hero-blank"></div>
        </section>
        <!-- Hero: End -->

        <!-- Useful features: Start -->
        <section id="landingFeatures" class="section-py landing-features mt-10">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Fonctionnalités Utiles</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">Tout ce dont vous avez besoin
                        <i class="fas fa-laptop section-title-icon position-absolute bottom-0 z-n1"></i>
                    </span>
                    pour gérer vos demandes de congé
                </h4>
                <p class="text-center mb-12">Plus qu'un simple outil, une solution complète pour la gestion des congés et
                    absences.</p>
                <div class="features-icon-wrapper row gx-0 gy-6 g-sm-12">
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <i class="fas fa-laptop fa-4x"></i>
                        </div>
                        <h5 class="mb-2">Interface Intuitive</h5>
                        <p class="features-icon-description">Une interface utilisateur simple et intuitive pour tous les
                            employés.</p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <i class="fas fa-envelope fa-4x"></i>
                        </div>
                        <h5 class="mb-2">Notifications Automatiques</h5>
                        <p class="features-icon-description">Recevez des notifications instantanées sur l'état de vos
                            demandes.</p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <i class="fas fa-clipboard-list fa-4x"></i>
                        </div>
                        <h5 class="mb-2">Suivi des Demandes</h5>
                        <p class="features-icon-description">Suivez facilement l'historique de vos demandes de congé.</p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle fa-4x"></i>
                        </div>
                        <h5 class="mb-2">Validation Rapide</h5>
                        <p class="features-icon-description">Validation rapide par les supérieurs hiérarchiques ou les RH.
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <i class="fas fa-users fa-4x"></i>
                        </div>
                        <h5 class="mb-2">Gestion des Employés</h5>
                        <p class="features-icon-description">Gérez facilement les informations et les demandes de vos
                            employés.</p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-tie fa-4x"></i>
                        </div>
                        <h5 class="mb-2">Gestion des Responsables</h5>
                        <p class="features-icon-description">Attribuez et gérez les rôles et permissions des responsables
                            hiérarchiques.</p>
                    </div>

                </div>
            </div>
        </section>
        <!-- Useful features: End -->


        <!-- Contact Us: Start -->
        <section id="landingContact" class="section-py bg-body landing-contact">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Contactez-nous</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">Travaillons ensemble
                        <i class="fas fa-handshake section-title-icon position-absolute bottom-0 z-n1"></i>
                    </span>
                </h4>
                <p class="text-center mb-12 pb-md-4">Une question ou une remarque ? N'hésitez pas à nous contacter
                    directement !</p>

                <div class="row g-6 justify-content-center">
                    <div class="col-lg-5">
                        <div class="contact-img-box position-relative border p-2 h-100">
                            <img src="{{ asset('assets/img/front-pages/icons/contact-border.png') }}" alt="contact border"
                                class="contact-border-img position-absolute d-none d-lg-block scaleX-n1-rtl" />
                            <img src="{{ asset('assets/img/front-pages/landing-page/contact-customer-service.png') }}"
                                alt="contact customer service" class="contact-img w-100 scaleX-n1-rtl" />
                            <div class="p-4 pb-2">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-label-primary rounded p-1_5 me-3"><i
                                                    class="ti ti-mail ti-lg"></i></div>
                                            <div>
                                                <p class="mb-0">Email</p>
                                                <h6 class="mb-0"><a href="mailto:example@gmail.com"
                                                        class="text-heading">example@gmail.com</a></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-label-success rounded p-1_5 me-3"><i
                                                    class="ti ti-phone-call ti-lg"></i></div>
                                            <div>
                                                <p class="mb-0">Téléphone</p>
                                                <h6 class="mb-0"><a href="tel:+1234-568-963"
                                                        class="text-heading">+22650505050
                                                    </a></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Us: End -->
    </div>
@endsection
