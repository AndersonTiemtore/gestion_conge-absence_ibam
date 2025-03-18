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
            <h5 class="card-title mb-0">Filtre</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">

                <div class="col-md-4">
                    <label>Nom</label>
                    <input type="search" wire:model.live.debounce.500ms='search' class="form-control"
                        placeholder="Rechercher un service...">
                </div>

                <div class="col-md-4 user_status"></div>
            </div>
        </div>

        <div class="row m-5">

            <div class="col-md-12">
                <div
                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0">

                    <div class="dt-buttons btn-group flex-wrap">
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'grh' ||
                                Illuminate\Support\Facades\Auth::user()->role == 'responsable')
                            <button class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><span><i
                                        class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">Ajouter un service</span></span>
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
                        <th>Nom</th>
                        {{-- <th>Responsable</th> --}}
                        <th>Description</th>
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'grh' ||
                                Illuminate\Support\Facades\Auth::user()->role == 'responsable')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($services->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun service trouvé.</td>
                        </tr>
                    @else
                        @foreach ($services as $service)
                            @php
                                $responsable = $service->users()->where('role', 'responsable')->first();
                            @endphp
                            <tr>
                                <th>{{ $service->nom }}</th>
                                {{-- <th>{{ $responsable->nom . ' ' . $responsable->prenom }}</th> --}}
                                <th>{{ !empty($service->description) ? $service->description : '-----' }}</th>
                                @if (Illuminate\Support\Facades\Auth::user()->role == 'grh' ||
                                        Illuminate\Support\Facades\Auth::user()->role == 'responsable')
                                    <td class="" style="">
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);"
                                                onclick="confirmDelete(event, '{{ $service->id }}')"
                                                class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                                <i class="ti ti-trash ti-md"></i>
                                            </a>

                                            <button wire:click="sendService('{{ $service->id }}')"
                                                class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                                data-id="11" data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvasStart" aria-controls="offcanvasStart">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="my-4">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        </div>


        {{--  --}}

        {{--  --}}

        <script>
            function confirmDelete(event, serviceId) {
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
                            title: 'Service supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteService', serviceId); // Appelez la méthode Livewire pour supprimer
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
