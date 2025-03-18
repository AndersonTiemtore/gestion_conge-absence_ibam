<div class="row">

    <div class="m-5">
        @if (session()->has('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="col-md-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);">
                        <i class="ri-group-line me-1_5"></i>Informations personnelles</a>
                </li>
            </ul>
        </div>
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body">

            </div>
            <div class="card-body pt-0">
                <form id="formAccountSettings" method="POST" wire:submit="update">
                    <div class="row mt-1 g-5">

                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input wire:model='nom' type="text" class="form-control" id="nom" />
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prenom</label>
                            <input wire:model='prenom' type="text" class="form-control" id="prenom" />
                            @error('prenom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input wire:model='email' type="text" class="form-control" id="email" />
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input wire:model='telephone' type="text" class="form-control" id="telephone" />
                            @error('telephone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input wire:model='adresse' type="text" class="form-control" id="adresse" />
                            @error('adresse')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="matricule" class="form-label">Matricule</label>
                            <input disabled wire:model='matricule' type="text" class="form-control" id="matricule" />
                            @error('matricule')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sexe" class="form-label">Sexe</label>
                            <input disabled wire:model='sexe' type="text" class="form-control" id="sexe" />
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input disabled wire:model='dateNaissance' type="text" class="form-control"
                                id="date_naissance" />
                            @error('dateNaissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="date_embauche" class="form-label">Date
                                d'embauche</label>
                            <input disabled wire:model='dateEmbauche' type="text" class="form-control"
                                id="date_embauche" />
                            @error('dateEmbauche')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="fonction" class="form-label">Fonction</label>
                            <input disabled wire:model='fonction' type="text" class="form-control" id="fonction" />
                            @error('fonction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label for="service" class="form-label">Service</label>
                            <input disabled wire:model='service' type="text" class="form-control"
                                id="service" />
                            @error('service')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <input disabled wire:model='role' type="text" class="form-control" id="role" />
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3" wire:loading.attr='disabled'>
                            <span wire:loading.remove> Sauvegarder </span>
                            <div wire:loading>
                                <span class="spinner-border" text-primary role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <button type="button" wire:click='reinitialiser'
                            class="btn btn-outline-secondary">Réinitialiser</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>

        {{-- <div class="card mt-6">
            <h5 class="card-header">Supprimer mon compte</h5>
            <div class="card-body">
                <form id="formAccountDeactivation" wire:submit="deleteAccount">
                    <div class="form-check mb-6 ms-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation"
                            id="accountActivation" wire:model.live="hasDeleted" />
                        <label class="form-check-label" for="accountActivation">
                            Je confirme la suppression de mon compte
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account"
                        {{ !$hasDeleted ? 'disabled' : '' }}>
                        Supprimer
                    </button>
                </form>
            </div>
        </div> --}}

    </div>

    <div
        wire:loading.class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
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
