<div>
    <div class="card">

        <div class="m-5">
            @if (session()->has('message'))
                <div class="alert alert-info">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filtres</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                <div class="col-md-4" wire:ignore>
                    <label for="selectService" class="form-label">Service</label>
                    <select wire:model='service' id="selectService" class="select2 form-select form-select-lg"
                        data-allow-clear="false">
                        <option value="">Selectionner le service</option>
                        @foreach ($services as $s)
                            <option value={{ $s->id }}>{{ $s->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4" wire:ignore>
                    <label for="selectFonction" class="form-label">Fonction</label>
                    <select wire:model='fonction' id="selectFonction" class="select2 form-select form-select-lg"
                        data-allow-clear="false">
                        <option value="">Selectionner la fonction</option>
                        @foreach ($fonctions as $f)
                            <option value={{ $f->id }}>{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4" wire:ignore>
                    <label for="selectRole" class="form-label">Role</label>
                    <select wire:model='role' id="selectRole" class="select2 form-select form-select-lg"
                        data-allow-clear="false">
                        <option value="">Selectionner le role</option>
                        <option value="responsable">Responsable</option>
                        <option value="employe">Employé</option>

                    </select>
                </div>

                <div class="col-md-4 mt-4">
                    <label>Nom ou Prénom</label>
                    <input type="search" wire:model.live.debounce.1000ms='search' class="form-control" placeholder="">
                </div>

                <div class="col-md-4 user_status"></div>
            </div>
        </div>

        <div class="row m-5">

            <div class="col-md-12">
                <div
                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0">

                    <div class="dt-buttons btn-group flex-wrap gap-5">
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'grh' ||
                                Illuminate\Support\Facades\Auth::user()->role == 'responsable')
                            <button class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4 ml-5"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><span><i
                                        class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">Ajouter un employé</span></span>
                            </button>
                            <button class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddResponsable"><span><i
                                        class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">Ajouter un responsable</span></span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Service</th>
                        <th>Fonction</th>
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'grh' ||
                                Illuminate\Support\Facades\Auth::user()->role == 'responsable')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                        </tr>
                    @else
                        @foreach ($users as $user)
                            <tr>
                                <th>{{ $user->matricule }}</th>
                                <th>{{ $user->nom }}</th>
                                <th>{{ $user->prenom }}</th>
                                <th>{{ $user->email }}</th>
                                <th>{{ $user->role }}</th>
                                <th>{{ $user->service->nom }}</th>
                                <th>{{ $user->fonction->nom }}</th>
                                @if (Illuminate\Support\Facades\Auth::user()->role == 'grh' ||
                                        Illuminate\Support\Facades\Auth::user()->role == 'responsable')
                                    <td class="" style="">
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);"
                                                onclick="confirmDelete(event, '{{ $user->id }}')"
                                                class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                                <i class="ti ti-trash ti-md"></i>
                                            </a>
                                            <button wire:click="sendUser('{{ $user->id }}')"
                                                class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                                data-id="11" data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvasStart" aria-controls="offcanvasStart">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <a href="javascript:void(0);"
                                                wire:click="showEmploye('{{ $user->id }}')"
                                                class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"
                                                data-bs-original-title="Voir" data-bs-toggle="modal"
                                                data-bs-target="#showEmploye"><i class="ti ti-eye ti-md"></i>
                                            </a>

                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="my-4">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>


        @include('_partials._modals.modal-show-employe');

        <script>
            function confirmDelete(event, employeId) {
                event.preventDefault();

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: 'Vous ne pourrez pas revenir en arrière !',
                    imageUrl: "{{ asset('assets/lordicon/delete.gif') }}",
                    // icon: 'warning',
                    imageWidth: 100, // Largeur du GIF
                    imageHeight: 100, // Hauteur du GIF
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: "success",
                            title: 'Employé(e) supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteEmploye', employeId); // Appelez la méthode Livewire pour supprimer
                    }
                });
            }
        </script>
        <!-- Offcanvas to add new user -->

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
            // $('#selectService').select2();
            $('#selectService').on('change', function(e) {
                var data = $('#selectService').select2("val");
                @this.set('service', data);

            });
        });
        $(document).ready(function() {
            // $('#selectService').select2();
            $('#selectFonction').on('change', function(e) {
                var data = $('#selectFonction').select2("val");
                @this.set('fonction', data);

            });
        });
        $(document).ready(function() {
            // $('#selectService').select2();
            $('#selectRole').on('change', function(e) {
                var data = $('#selectRole').select2("val");
                @this.set('role', data);

            });
        });
    </script>
@endscript
