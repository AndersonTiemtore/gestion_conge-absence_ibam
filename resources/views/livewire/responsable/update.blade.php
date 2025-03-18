<div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStartResponsable"
    aria-labelledby="offcanvasStartLabelResponsable">

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Modifier responsable</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="add-new-user pt-0" id="addNewUserFormResponsable" wire:submit='update'>
            <div class="mb-6">
                <label class="form-label" for="add-user-contact">Matricule</label>
                <input type="text" wire:model='matricule' id="add-user-contact"
                    class="form-control phone-mask @error('matricule') is-invalid @enderror" name="matricule" />
                @error('matricule')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="nom">Nom</label>
                <input type="text" wire:model='nom' class="form-control @error('nom') is-invalid @enderror"
                    id="nom" name="nom" />
                @error('nom')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="prenom">Prénom</label>
                <input type="text" wire:model='prenom' class="form-control @error('prenom') is-invalid @enderror"
                    id="prenom" name="prenom" />
                @error('prenom')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="add-user-email">Email</label>
                <input type="text" wire:model='email' id="add-user-email"
                    class="form-control @error('email') is-invalid @enderror" name="email" />
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <div wire:ignore>
                    <label class="form-label" for="service-update-responsable">Service</label>
                    <select id="service-update-responsable"
                        class="select2 form-select @error('service') is-invalid @enderror">
                        <option value="">Choisir le service</option>
                    </select>
                </div>
                @error('service')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <div wire:ignore>
                    <label class="form-label" for="fonction-update-responsable">Fonction</label>
                    <select id="fonction-update-responsable"
                        class="select2 form-select @error('fonction') is-invalid @enderror">
                        <option value="">Choisir la fonction</option>
                    </select>
                </div>
                @error('fonction')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="adresse">Adresse</label>
                <input type="text" wire:model='adresse' class="form-control @error('adresse') is-invalid @enderror"
                    id="adresse" name="adresse" />
                @error('adresse')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="telephone">Téléphne</label>
                <input type="text" wire:model='telephone'
                    class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" />
                @error('telephone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <sapn class="fw-medium d-block">Sexe</sapn>
                <div class="form-check form-check-inline mt-4">
                    <input wire:model='sexe' class="form-check-input @error('sexe') is-invalid @enderror" type="radio"
                        name="sexe" id="masc" value="masculin" />
                    <label class="form-check-label" for="masc">Masculin</label>
                </div>
                <div class="form-check form-check-inline">
                    <input wire:model='sexe' class="form-check-input @error('sexe') is-invalid @enderror" type="radio"
                        name="sexe" id="fem" value="feminin" />
                    <label class="form-check-label" for="fem">Féminin</label>
                </div>
                @error('sexe')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="flatpickr-date-debut" class="form-label">Date de naissance</label>
                <input wire:model='dateNaissance' type="text" name="date-naissance" class="form-control"
                    placeholder="YYYY-MM-DD" id="flatpickr-date-debut" />
                @error('dateNaissance')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="flatpickr-date-fin" class="form-label">Date d'embauche</label>
                <input wire:model='dateEmbauche' type="text" name="date-fin" class="form-control"
                    placeholder="YYYY-MM-DD" id="flatpickr-date-fin" />
                @error('dateEmbauche')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Mettre à jour</span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Chargement...
                </span>
            </button>

            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Fermer</button>
        </form>
    </div>
</div>


@script
    <script>
        $(document).ready(function() {

            $('#service-update-responsable').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('service', data); // Met à jour la propriété Livewire
                // @this.call('selectNiveau');
            });
        });

        $(document).ready(function() {

            $('#fonction-update-responsable').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('fonction', data); // Met à jour la propriété Livewire
                // @this.call('selectNiveau');
            });
        });

        // Initialisation des select avec les infos de l'utilisateur
        $wire.on('update-event', () => {

            // service
            let serviceSelectionne = @this.get('service'); // Récupère la filière sélectionnée

            @this.call('getServices').then(services => {

                // Vider les options actuelles
                $('#service-update-responsable').empty();

                // Ajouter l'option par défaut
                $('#service-update-responsable').append(
                    '<option value="">Sélectionnez un service</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner la bonne filière
                services.forEach(function(service) {

                    // Vérifier si cette filière correspond à celle sélectionnée
                    let selected = (service.id == serviceSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si la filière correspond
                    $('#service-update-responsable').append('<option value="' + service.id + '" ' +
                        selected +
                        '>' + service
                        .nom + '</option>');
                });

            });

            //Fonction
            let fonctionSelectionne = @this.get('fonction'); // Récupère la filière sélectionnée

            @this.call('getFonctions').then(fonctions => {

                // Vider les options actuelles
                $('#fonction-update-responsable').empty();

                // Ajouter l'option par défaut
                $('#fonction-update-responsable').append(
                    '<option value="">Sélectionnez une fonction</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner la bonne filière
                fonctions.forEach(function(fonction) {

                    // Vérifier si cette filière correspond à celle sélectionnée
                    let selected = (fonction.id == fonctionSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si la filière correspond
                    $('#fonction-update-responsable').append('<option value="' + fonction.id +
                        '" ' + selected +
                        '>' + fonction
                        .nom + '</option>');
                });

            });

        });
    </script>
@endscript
