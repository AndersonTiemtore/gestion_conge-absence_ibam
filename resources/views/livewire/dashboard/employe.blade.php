<div>
    @php
        $notifications = App\Models\User::query()->where('role', 'grh')->first()->notifications()->get();
    @endphp
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

    <div class="row g-6 mb-5">

        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger"><i
                                    class='ti ti-alert-circle ti-28px'></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $demandesAttentes->count() }}</h4>
                    </div>
                    <p class="mb-1">Total</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">Demandes en attente</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-secondary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class='ti ti-calendar ti-28px'></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $demandesPlannifiees->count() }}</h4>
                    </div>
                    <p class="mb-1">Total</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">Demandes plannifiées</span>
                    </p>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success"><i class='ti ti-check ti-28px'></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $demandesAcceptees->count() }}</h4>
                    </div>
                    <p class="mb-1">Total</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">Demandes acceptées</span>
                    </p>
                </div>
            </div>
        </div>

        

        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-secondary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-secondary"><i class='ti ti-x ti-28px'></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $demandesRefusees->count() }}</h4>
                    </div>
                    <p class="mb-1">Total</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">Demandes refusées</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class='ti ti-calendar-stats ti-28px'></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $joursRestants }}</h4>
                    </div>
                    <p class="mb-1">sur {{ self::JOURS_CONGES_ANNUELS }} jours</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">Jours de congés restants</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div
                class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0">

                @if ($responsableService && $joursRestants > 0)
                    <div class="dt-buttons btn-group flex-wrap">
                        <button wire:click='reinitislisation'
                            class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4" 
                            tabindex="0"
                            aria-controls="DataTables_Table_0" 
                            type="button" 
                            data-bs-toggle="modal"
                            data-bs-target="#demande-create">
                            <span>
                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">Créer une demande</span>
                            </span>
                        </button>
                    </div>
                @elseif ($joursRestants <= 0)
                    <div class="alert alert-warning mt-3" role="alert">
                        <h5 class="alert-heading">Impossible de créer une demande</h5>
                        <p>Vous avez épuisé tous vos jours de congés pour cette année.</p>
                        <hr>
                        <p class="mb-0">Le quota sera renouvelé au début de la prochaine année.</p>
                    </div>
                @else
                    <div class="alert alert-danger mt-3" role="alert">
                        <h5 class="alert-heading">Impossible de créer une demande</h5>
                        <p>Vous ne pouvez pas soumettre une demande d'absence ou de congé car aucun responsable n'est
                            assigné à votre service.</p>
                        <hr>
                        <p class="mb-0">Veuillez contacter votre administration pour résoudre ce problème.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Conges actifs  --}}
    <div class="row g-6 mb-10">
        <div class="col-12">
            <h4 class="mb-4">📅 Mes demandes de congé en cours</h4>
        </div>

        @if ($demandesActives->isEmpty())
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="ti ti-info-circle"></i> Vous n'avez aucune demande de congé en cours.
                </div>
            </div>
        @else
            @foreach ($demandesActives as $demande)
                @php
                    $dateDebut = \Carbon\Carbon::parse($demande->date_debut);
                    $dateFin = \Carbon\Carbon::parse($demande->date_fin);
                    $duree = $dateDebut->diffInDays($dateFin) + 1; // +1 pour inclure le dernier jour
                @endphp
                <div class="col-12 col-lg-4 ps-md-4 ps-lg-6">
                    <div class="card shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Période de congé</h5>
                                <p class="mb-2 text-muted">
                                    {{ $dateDebut->translatedFormat('d M Y') }} -
                                    {{ $dateFin->translatedFormat('d M Y') }}
                                </p>
                                <span class="badge bg-label-info">{{ $demande->type_conge }}</span>
                            </div>
                            <div class="text-end">
                                <h4 class="mb-2">{{ $duree }}<span class="text-body">j</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    {{-- Conges actifs  --}}

    <!-- Demandes -->
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Demandes</h5>
                    <p class="card-subtitle">Suivi des demandes</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs nav-fill rounded-0 timeline-indicator-advanced" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-demander" aria-controls="navs-justified-demander"
                                aria-selected="true">Demandées</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-plannifier" aria-controls="navs-justified-plannifier"
                                aria-selected="false">Plannifiées</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-accepter" aria-controls="navs-justified-accepter"
                                aria-selected="false">Acceptées</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-refuser" aria-controls="navs-justified-refuser"
                                aria-selected="false">Rejetées</button>
                        </li>
                    </ul>
                    <div class="tab-content border-0 mx-1">
                        <!-- Tab 1: Demander -->
                        <div class="tab-pane fade show active" id="navs-justified-demander" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>EMPLOYé</th>
                                            <th>DATE Début</th>
                                            <th>DATE FIN</th>
                                            <th>MOTIF</th>
                                            <th>TYPE</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $notificationDemandeIds = $notifications->pluck('data.demande')->toArray();
                                        @endphp

                                        @forelse ($demandesAttentes as $d)
                                            <tr>
                                                <td class="pt-4">
                                                    {{ $d->employe->user->nom . ' ' . $d->employe->user->prenom }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($d->date_debut)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($d->date_fin)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">{{ $d->motif }}</td>
                                                <td class="pt-4">
                                                    <span class="badge bg-label-warning">{{ $d->type_conge }}</span>
                                                </td>
                                                <td class="pt-4">
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                                            type="button" id="coursActions{{ $d->id }}"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical ti-md text-muted"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @if (in_array($d->id, $notificationDemandeIds))
                                                                <button class="dropdown-item" disabled>Demande de
                                                                    modification en attente</button>
                                                            @else
                                                                <button
                                                                    wire:click='demandeModification("{{ $d->id }}")'
                                                                    class="dropdown-item">Demander la modification de
                                                                    cette demande</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center pt-4">
                                                    <p class="text-muted">Aucune demande en attente pour le moment.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <!-- Tab 2: Plannifier -->
                        <div class="tab-pane fade" id="navs-justified-plannifier" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>EMPLOYé</th>
                                            <th>DATE Début</th>
                                            <th>DATE FIN</th>
                                            <th>MOTIF</th>
                                            <th>TYPE</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($demandesPlannifiees as $dp)
                                            <tr>
                                                <td class="pt-4">
                                                    {{ $dp->employe->user->nom . ' ' . $dp->employe->user->prenom }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($dp->date_debut)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($dp->date_fin)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">{{ $dp->motif }}</td>

                                                <td class="pt-4"><span
                                                        class="badge bg-label-warning">{{ $dp->type_conge }}</span>
                                                </td>
                                                <td class="pt-4">
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                                            type="button" id="coursActions{{ $dp->id }}"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical ti-md text-muted"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <button wire:click='changerStatut("{{ $dp->id }}")'
                                                                class="dropdown-item" href="javascrip();">
                                                                Changer le statut à "demandée"
                                                            </button>
                                                            <button tabindex="0" aria-controls="DataTables_Table_0"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#demande-update"
                                                                wire:click='modifierDemande("{{ $dp->id }}")'
                                                                class="dropdown-item" href="javascrip();">
                                                                Modifier la demande
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center pt-4">
                                                    <p class="text-muted">Aucune demande en attente pour le moment.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 3: Accepter -->
                        <div class="tab-pane fade" id="navs-justified-accepter" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>EMPLOYé</th>
                                            <th>DATE Début</th>
                                            <th>DATE FIN</th>
                                            <th>MOTIF</th>
                                            <th>TYPE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($demandesAcceptees as $da)
                                            <tr>
                                            <tr>
                                                <td class="pt-4">
                                                    {{ $da->employe->user->nom . ' ' . $da->employe->user->prenom }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($da->date_debut)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($da->date_fin)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">{{ $da->motif }}</td>

                                                <td class="pt-4"><span
                                                        class="badge bg-label-warning">{{ $da->type_conge }}</span>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center pt-4">
                                                    <p class="text-muted">Aucune demande en attente pour le moment.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 4: Refuser -->
                        <div class="tab-pane fade" id="navs-justified-refuser" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>EMPLOYé</th>
                                            <th>DATE Début</th>
                                            <th>DATE FIN</th>
                                            <th>MOTIF</th>
                                            <th>TYPE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($demandesRefusees as $dr)
                                            <tr>
                                                <td class="pt-4">
                                                    {{ $dr->employe->user->nom . ' ' . $dr->employe->user->prenom }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($dr->date_debut)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">
                                                    {{ \Carbon\Carbon::parse($dr->date_fin)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="pt-4">{{ $dr->motif }}</td>

                                                <td class="pt-4"><span
                                                        class="badge bg-label-warning">{{ $dr->type_conge }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center pt-4">
                                                    <p class="text-muted">Aucune demande en attente pour le moment.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Demandes -->


    @include('_partials/_modals/modal-create-demande')
    @include('_partials/_modals/modal-edit-demande')

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

@script
    <script>
        @if (session('message'))

            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ session('message') }}',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center',
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                title: 'Erreur',
                icon: 'error',
                text: '{{ session('error') }}',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center',
            });
        @endif

        $(document).ready(function() {
            $('#type_conge-md').on('change', function(e) {
                var data = $('#type_conge-md').select2("val");
                @this.set('typeConge', data);

            });
        });

        $wire.on('event-update', (demandeId) => {
            const typeConge = demandeId[0].id
            $('#type_conge-md').val(typeConge).trigger('change');
        });
    </script>
@endscript
