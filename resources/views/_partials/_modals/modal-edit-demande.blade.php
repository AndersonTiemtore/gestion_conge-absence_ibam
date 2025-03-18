<div wire:ignore.self class="modal fade" id="demande-update" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Modification d'une demande d'absence/congé</h4>
                    {{-- <p>Updating user details will receive a privacy audit.</p> --}}
                </div>
                <form id="editUserForm" class="row g-6" wire:submit.prevent='update'>
                    <div class="col-12 col-md-6">
                        <label for="flatpickr-date-debut" class="form-label">Date de début</label>
                        <input wire:model='dateDebut' type="text" name="date-debut" class="form-control"
                            placeholder="YYYY-MM-DD" id="flatpickr-date-debut" />
                        @error('dateDebut')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="flatpickr-date-fin" class="form-label">Date de fin</label>
                        <input wire:model='dateFin' type="text" name="date-fin" class="form-control"
                            placeholder="YYYY-MM-DD" id="flatpickr-date-fin" />
                        @error('dateFin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="motif">Motif</label>
                        <textarea wire:model='motif' class="form-control" id="motif" rows="3"></textarea>
                        @error('motif')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <div wire:ignore>
                            <label class="form-label" for="type_conge-md">Type de congé/absence</label>
                            <select wire:model='typeConge' id="type_conge-md" name="type_conge"
                                class="select2 form-select" aria-label="Default select example">
                                <option value="">Faites votre choix</option>
                                <option value="compensation">Compensation</option>
                                <option value="conge_payé">Congés payés</option>
                                <option value="maternité">Maternité</option>
                                <option value="paternité">Paternité</option>
                                <option value="ancienneté">Ancienneté</option>
                                <option value="maladie">Maladie</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        @error('typeConge')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserLanguage">Statut</label>
                        <small class="text-light fw-medium d-block"></small>
                        <div class="form-check form-check-inline mt-4">
                            <input wire:model='statutDemande' class="form-check-input" type="radio" name="statut"
                                id="inlineRadio1"
                                value={{ App\Models\StatutDemande::query()->where('statut', 'plannifier')->first()->id }} />
                            <label class="form-check-label" for="inlineRadio1">Plannifiée</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input wire:model='statutDemande' class="form-check-input" type="radio" name="statut"
                                id="inlineRadio2" value={{ $statutAttente->id }} />
                            <label class="form-check-label" for="inlineRadio2">Demandée</label>
                        </div>
                        @error('statutDemande')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>Mettre à jour</span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Chargement...
                            </span>
                        </button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Fermer</button>
                    </div>
                </form>

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

@script
    <script>
        $(document).ready(function() {

            $('#type_conge').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('typeConge', data); // Met à jour la propriété Livewire
                // @this.call('selectNiveau');
            });
        });
    </script>
@endscript
