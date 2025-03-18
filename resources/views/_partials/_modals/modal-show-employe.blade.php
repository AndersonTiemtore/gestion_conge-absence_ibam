<div wire:ignore.self class="modal fade" id="showEmploye" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h4 class="mb-2">Détails de l'Employé</h4>
                    <p class="text-muted">Informations personnelles et professionnelles</p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nom :</strong> {{ $nom }}</p>
                        <p><strong>Prénom :</strong> {{ $prenom }}</p>
                        <p><strong>Email :</strong> {{ $email }}</p>
                        <p><strong>Téléphone :</strong> {{ $telephone }}</p>
                        <p><strong>Adresse :</strong> {{ $adresse }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Matricule :</strong> {{ $matricule }}</p>
                        <p><strong>Sexe :</strong> {{ $sexe }}</p>
                        <p><strong>Naissance :</strong>
                            {{ \Carbon\Carbon::parse($dateNaissance)->translatedFormat('d M Y') }}</p>
                        <p><strong>Embauche :</strong>
                            {{ \Carbon\Carbon::parse($dateEmbauche)->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>Fonction :</strong> {{ $fonctionShow }}</p>
                        <p><strong>Service :</strong> {{ $serviceShow }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Rôle :</strong> {{ $roleShow }}</p>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
        <div wire:loading class="sk-chase sk-primary">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
    </div>
</div>
