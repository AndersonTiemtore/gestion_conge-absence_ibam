<!-- Footer: Start -->
<footer class="landing-footer bg-body footer-text">

    <div class="footer-top position-relative overflow-hidden z-1">
        <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg-' . $configData['style'] . '.png') }}"
            alt="footer bg" class="footer-bg banner-bg-img z-n1"
            data-app-light-img="front-pages/backgrounds/footer-bg-light.png"
            data-app-dark-img="front-pages/backgrounds/footer-bg-dark.png" />
        <div class="container">
            <div class="row gx-0 gy-6 g-lg-10">
                <div class="col-lg-5">
                    <a href="{{ url('front-pages/landing') }}" class="app-brand-link mb-6">
                        <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                        <span
                            class="app-brand-text demo footer-link fw-bold ms-2 ps-1">{{ config('variables.templateName') }}
                            . Tous droits réservés.
                        </span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <div class="footer-bottom py-3 py-md-5">
        <div
            class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
            <div class="mb-2 mb-md-0">
                <span class="footer-bottom-text">©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                </span>
                <a href="{{ config('variables.creatorUrl') }}" target="_blank"
                    class="fw-medium text-white text-white">{{ config('variables.creatorName') }},</a>
                <span class="footer-bottom-text"> Conçu avec soin pour simplifier la gestion des congés et des absences.
                </span>
            </div>
        </div>
    </div>
</footer>
<!-- Footer: End -->
