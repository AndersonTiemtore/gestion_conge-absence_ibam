<?php

namespace App\Livewire\Dashboard;

use App\Mail\DemandeCongeMail;
use App\Models\DemandeConge;
use App\Models\StatutDemande;
use App\Models\User;
use App\Notifications\DemandeCongeNotification;
use App\Notifications\ModificationDemandeNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Employe extends Component
{
    public $user;
    public $employe;
    public $responsableService;
    public $joursRestants;

    public const JOURS_CONGES_ANNUELS = 30;

    public $demandesAttentes;
    public $demandesAcceptees;
    public $demandesRefusees;
    public $demandesPlannifiees;
    public $demandesActives;

    public $statutAttente;
    public $statutAccepter;
    public $statutRefuser;
    public $statutPlannifier;

    // Champs de validation du formulaire
    public $id;
    public $dateDebut;
    public $dateFin;
    public $motif;
    public $typeConge;
    public $statutDemande;

    protected function rules()
    {
        return [
            'dateDebut' => ['required', 'date', 'after_or_equal:today'],
            'dateFin' => ['required', 'date', 'after:dateDebut'],
            'motif' => ['required', 'string', 'min:10'],
            'typeConge' => ['required', 'in:compensation,conge_payé,maternité,ancienneté,paternité,maladie,autre'],
            'statutDemande' => ['required', 'exists:statut_demandes,id'],
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->employe = $this->user->employe;

        $this->responsableService = User::query()
            ->where(['role' => 'responsable', 'service_id' => $this->user->service_id])
            ->first();

        $statuts = StatutDemande::whereIn('statut', ['demander', 'plannifier', 'accepter', 'refuser'])
            ->get()
            ->keyBy('statut');

        $this->statutAttente = $statuts['demander'] ?? null;
        $this->statutPlannifier = $statuts['plannifier'] ?? null;
        $this->statutAccepter = $statuts['accepter'] ?? null;
        $this->statutRefuser = $statuts['refuser'] ?? null;

        // Récupérer toutes les demandes de l'employé
        $demandes = $this->employe->demandes()->get();

        // Filtrer les demandes
        $this->demandesAttentes = $demandes->where('statut_demande_id', $this->statutAttente?->id);
        $this->demandesAcceptees = $demandes->where('statut_demande_id', $this->statutAccepter?->id);
        $this->demandesRefusees = $demandes->where('statut_demande_id', $this->statutRefuser?->id);
        $this->demandesPlannifiees = $demandes->where('statut_demande_id', $this->statutPlannifier?->id);
        $this->demandesActives = $this->demandesAcceptees->where('date_fin', '>=', now()->toDateString());

        // Calculer les jours de congés restants
        $this->calculerJoursRestants();
    }

    private function calculerJoursRestants()
    {
        $demandesAnnee = DemandeConge::where('employe_id', $this->employe->id)
            ->whereIn('statut_demande_id', [$this->statutAccepter?->id, $this->statutAttente?->id])
            ->whereYear('date_debut', date('Y'))
            ->get();

        $joursPris = 0;
        foreach ($demandesAnnee as $demande) {
            $debut = Carbon::parse($demande->date_debut);
            $fin = Carbon::parse($demande->date_fin);
            $joursPris += $debut->diffInDays($fin) + 1;
        }

        $this->joursRestants = self::JOURS_CONGES_ANNUELS - $joursPris;
    }

    public function reinitislisation()
    {
        $this->reset(['id', 'dateDebut', 'dateFin', 'motif', 'typeConge', 'statutDemande']);
    }

    public function submit()
    {
        $this->validate();

        // Calculer la durée de la nouvelle demande
        $debut = Carbon::parse($this->dateDebut);
        $fin = Carbon::parse($this->dateFin);
        $dureedemande = $debut->diffInDays($fin) + 1;

        // Vérifier si l'employé a assez de jours restants
        if ($dureedemande > $this->joursRestants) {
            return redirect()->route('dashboard')->with('error',
                "Impossible de créer la demande. Il vous reste {$this->joursRestants} jours de congés disponibles pour l'année en cours.");
        }

        // Vérifier si une demande de congé existe déjà pour cette période
        $chevauchement = DemandeConge::where('employe_id', $this->employe->id)
            ->where(function ($query) {
                $query->whereBetween('date_debut', [$this->dateDebut, $this->dateFin])
                    ->orWhereBetween('date_fin', [$this->dateDebut, $this->dateFin])
                    ->orWhere(function ($query) {
                        $query->where('date_debut', '<=', $this->dateDebut)
                            ->where('date_fin', '>=', $this->dateFin);
                    });
            })
            ->whereIn('statut_demande_id', [$this->statutAttente->id, $this->statutAccepter->id])
            ->exists();

        if ($chevauchement) {
            return redirect()->route('dashboard')->with('error',
                'Vous avez déjà une demande de congé ou un congé actif sur cette période.');
        }

        $demande = new DemandeConge();
        $demande->date_debut = $this->dateDebut;
        $demande->date_fin = $this->dateFin;
        $demande->date_soumission = now();
        $demande->motif = $this->motif;
        $demande->type_conge = $this->typeConge;
        $demande->statut_demande_id = $this->statutDemande;
        $demande->employe_id = $this->employe->id;

        if ($this->statutDemande == $this->statutAttente->id) {
            try {
                $demande->save();
                Mail::to($this->responsableService->email)->send(new DemandeCongeMail(Auth::user(), $demande));
                Notification::send($this->responsableService, new DemandeCongeNotification($demande));

                return redirect()->route('dashboard')->with('message', 'Demande absence/congé ajoutée avec succès');
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Une erreur est survenue. Envoi du mail échoué');
            }
        } else {
            $demande->save();

            return redirect()->route('dashboard')->with('message', 'Demande absence/congé ajoutée avec succès');
        }
    }

    public function update()
    {
        $this->validate();

        // Calculer la durée de la demande mise à jour
        $debut = Carbon::parse($this->dateDebut);
        $fin = Carbon::parse($this->dateFin);
        $dureedemande = $debut->diffInDays($fin) + 1;

        // Vérifier si l'employé a assez de jours restants
        if ($dureedemande > $this->joursRestants) {
            return redirect()->route('dashboard')->with('error',
                "Impossible de modifier la demande. Il vous reste {$this->joursRestants} jours de congés disponibles pour l'année en cours.");
        }

        // Vérifier si une demande de congé existe déjà pour cette période
        $chevauchement = DemandeConge::where('employe_id', $this->employe->id)
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->whereBetween('date_debut', [$this->dateDebut, $this->dateFin])
                    ->orWhereBetween('date_fin', [$this->dateDebut, $this->dateFin])
                    ->orWhere(function ($query) {
                        $query->where('date_debut', '<=', $this->dateDebut)
                            ->where('date_fin', '>=', $this->dateFin);
                    });
            })
            ->whereIn('statut_demande_id', [$this->statutAttente->id, $this->statutAccepter->id])
            ->exists();

        if ($chevauchement) {
            return redirect()->route('dashboard')->with('error',
                'Vous avez déjà une demande de congé ou un congé actif sur cette période.');
        }

        $demande = DemandeConge::query()->findOrFail($this->id);
        $demande->date_debut = $this->dateDebut;
        $demande->date_fin = $this->dateFin;
        $demande->motif = $this->motif;
        $demande->type_conge = $this->typeConge;
        $demande->statut_demande_id = $this->statutDemande;
        $demande->employe_id = $this->employe->id;

        if ($this->statutDemande == $this->statutAttente->id) {
            try {
                Mail::to($this->responsableService->email)->send(new DemandeCongeMail(Auth::user(), $demande));
                Notification::send($this->responsableService, new DemandeCongeNotification($demande));
                $demande->date_soumission = now();
                $demande->update();

                return redirect()->route('dashboard')->with('message', 'Demande absence/congé mise à jour avec succès');
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Une erreur est survenue. Envoi du mail échoué');
            }
        } else {
            $demande->update();

            return redirect()->route('dashboard')->with('message', 'Demande absence/congé mise à jour avec succès');
        }
    }

    public function changerStatut(DemandeConge $demande)
    {
        // Calculer la durée de la demande
        $debut = Carbon::parse($demande->date_debut);
        $fin = Carbon::parse($demande->date_fin);
        $dureedemande = $debut->diffInDays($fin) + 1;

        // Vérifier si l'employé a assez de jours restants
        if ($dureedemande > $this->joursRestants) {
            return redirect()->route('dashboard')->with('error',
                "Impossible de changer le statut. Il vous reste {$this->joursRestants} jours de congés disponibles pour l'année en cours.");
        }

        // Vérifier si une demande de congé existe déjà pour cette période
        $chevauchement = DemandeConge::where('employe_id', $demande->employe_id)
            ->where('id', '!=', $demande->id)
            ->where(function ($query) use ($demande) {
                $query->whereBetween('date_debut', [$demande->date_debut, $demande->date_fin])
                    ->orWhereBetween('date_fin', [$demande->date_debut, $demande->date_fin])
                    ->orWhere(function ($query) use ($demande) {
                        $query->where('date_debut', '<=', $demande->date_debut)
                            ->where('date_fin', '>=', $demande->date_fin);
                    });
            })
            ->whereIn('statut_demande_id', [$this->statutAttente->id, $this->statutAccepter->id])
            ->exists();

        if ($chevauchement) {
            return redirect()->route('dashboard')->with('error',
                'Vous avez déjà une demande de congé ou un congé actif sur cette période.');
        }

        try {
            Mail::to($this->responsableService->email)->send(new DemandeCongeMail(Auth::user(), $demande));
            Notification::send($this->responsableService, new DemandeCongeNotification($demande));

            $demande->statut_demande_id = $this->statutAttente->id;
            $demande->date_soumission = now();
            $demande->update();

            return redirect()->route('dashboard')->with('message', 'Une demande a changé de statut (planifiée)');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue. Envoi du mail échoué');
        }
    }

    public function modifierDemande(DemandeConge $demande)
    {
        $this->id = $demande->id;
        $this->dateDebut = $demande->date_debut;
        $this->dateFin = $demande->date_fin;
        $this->motif = $demande->motif;
        $this->typeConge = $demande->type_conge;
        $this->statutDemande = $demande->statut_demande_id;
        $this->dispatch('event-update', ['id' => $this->typeConge])->self();
    }

    public function demandeModification(DemandeConge $demande)
    {
        $grh = User::query()->where('role', 'grh')->first();
        Notification::send($grh, new ModificationDemandeNotification($demande, Auth::user()));

        return redirect()->route('dashboard')->with('message', 'Demande de modification envoyée avec succès');
    }

    public function render()
    {
        return view('livewire.dashboard.employe');
    }
}
