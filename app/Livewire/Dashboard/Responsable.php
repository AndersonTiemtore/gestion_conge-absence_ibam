<?php

namespace App\Livewire\Dashboard;

use App\Mail\DemandeCongeMail;
use App\Mail\ResponseDemandeMail;
use App\Models\DemandeConge;
use App\Models\StatutDemande;
use App\Models\User;
use App\Notifications\DemandeCongeNotification;
use App\Notifications\ModificationDemandeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Responsable extends Component
{

    public $user;
    public $service;
    public $employe;

    public $employes; // Liste des employes
    public $grh;

    public $demandesAttentes;
    public $demandesAcceptees;
    public $demandesRefusees;
    public $demandesActives; // Les demandes acceptees qui sont actives

    public $mesDemandesAttentes;
    public $mesDemandesAcceptees;
    public $mesDemandesRefusees;
    public $mesDemandesActives; // Les demandes acceptees qui sont actives
    public $mesDemandesPlannifiees;


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
    public $dateSoumission;


    //Employe
    public $matricule = '';
    public $nom = '';
    public $prenom = '';
    public $adresse = '';
    public $email = '';
    public $telephone = '';
    public $dateNaissance = '';
    public $dateEmbauche = '';
    public $sexe = '';
    public $serviceShow = '';
    public $fonctionShow;
    public $roleShow;


    protected function rules()
    {
        return [
            'dateDebut'     => ['required', 'date', 'after_or_equal:today'],
            'dateFin'       => ['required', 'date', 'after:dateDebut'],
            'motif'         => ['required', 'string', 'min:10'],
            'typeConge'     => ['required', 'in:compensation,conge_payé,maternité,ancienneté,paternité,maladie,autre'],
            'statutDemande' => ['required', 'exists:statut_demandes,id'],
        ];
    }


    public function mount()
    {
        $this->user = Auth::user();
        $this->employe = $this->user->employe;
        $this->service = $this->user->service;
        $this->employes = $this->service->users()->where('role', 'employe')->get();
        $this->grh = User::query()->where('role', 'grh')->first();

        $statuts = StatutDemande::whereIn('statut', ['demander', 'accepter', 'refuser', 'plannifier'])
            ->get()
            ->keyBy('statut');

        $this->statutAttente = $statuts['demander'] ?? null;
        $this->statutAccepter = $statuts['accepter'] ?? null;
        $this->statutRefuser = $statuts['refuser'] ?? null;
        $this->statutPlannifier = $statuts['plannifier'] ?? null;
        

        // ********************************************************************
        // Récupérer toutes les demandes de mes employes en une seule requête
        $demandes = DemandeConge::query()
            ->whereHas('employe.user', function ($query) {
                $query->where('service_id', $this->service->id)
                    ->where('role', 'employe'); // Filtre les utilisateurs avec le rôle "employe"
            })
            ->whereIn('statut_demande_id', [
                optional($this->statutAttente)->id,
                optional($this->statutAccepter)->id,
                optional($this->statutRefuser)->id,
            ])
            ->with('employe.user')
            ->get();

        // Filtrer les demandes
        $this->demandesAttentes = $demandes->where('statut_demande_id', $this->statutAttente?->id);
        $this->demandesAcceptees = $demandes->where('statut_demande_id', $this->statutAccepter?->id);
        $this->demandesRefusees = $demandes->where('statut_demande_id', $this->statutRefuser?->id);
        $this->demandesActives = $this->demandesAcceptees->where('date_fin', '>=', now()->toDateString());

        // ********************************************************************


        // Récupérer toutes les demandes de l'employé en une seule requête
        $mesDemandes = $this->employe->demandes()->get();

        // Filtrer les demandes
        $this->mesDemandesAttentes = $mesDemandes->where('statut_demande_id', $this->statutAttente?->id);
        $this->mesDemandesAcceptees = $mesDemandes->where('statut_demande_id', $this->statutAccepter?->id);
        $this->mesDemandesRefusees = $mesDemandes->where('statut_demande_id', $this->statutRefuser?->id);
        $this->mesDemandesPlannifiees = $mesDemandes->where('statut_demande_id', $this->statutPlannifier?->id);
        $this->mesDemandesActives = $this->mesDemandesAcceptees->where('date_fin', '>=', now()->toDateString());
    }

    public function reinitislisation()
    {
        $this->reset(['id', 'dateDebut', 'dateFin', 'motif', 'typeConge', 'statutDemande']);
    }

    public function submit()
    {
        $this->validate();

        // Vérifier si une demande de congé existe déjà pour cette période
        $chevauchement = DemandeConge::where('employe_id', $this->user->employe->id)
            ->where(function ($query) {
                $query->whereBetween('date_debut', [$this->dateDebut, $this->dateFin])
                    ->orWhereBetween('date_fin', [$this->dateDebut, $this->dateFin])
                    ->orWhere(function ($query) {
                        $query->where('date_debut', '<=', $this->dateDebut)
                            ->where('date_fin', '>=', $this->dateFin);
                    });
            })
            ->whereIn('statut_demande_id', [$this->statutAttente->id, $this->statutAccepter->id]) // Seulement les demandes actives ou en attente
            ->exists();

        if ($chevauchement) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une demande de congé ou un congé actif sur cette période.');
        }

        $demande = new DemandeConge();
        $demande->date_debut = $this->dateDebut;
        $demande->date_fin = $this->dateFin;
        $demande->date_soumission = now();
        $demande->motif = $this->motif;
        $demande->type_conge = $this->typeConge;
        $demande->statut_demande_id = $this->statutDemande;
        $demande->employe_id = $this->user->employe->id;
        if ($this->statutDemande == $this->statutAttente->id) {
            try {
                $demande->save();
                Mail::to($this->grh->email)->send(new DemandeCongeMail($this->user, $demande));
                Notification::send($this->grh, new DemandeCongeNotification($demande));
                return redirect()->route('dashboard')->with('message', 'Demande absence/congé ajoutée avec succès');
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Une erreur est survenue. Envoie du mail echoué');
            }
        } else {
            $demande->save();
            return redirect()->route('dashboard')->with('message', 'Demande absence/congé ajoutée avec succès');
        }
    }

    public function update()
    {
        $this->validate();

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
            ->whereIn('statut_demande_id', [$this->statutAttente->id, $this->statutAccepter->id]) // Seulement les demandes actives ou en attente
            ->exists();

        if ($chevauchement) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une demande de congé ou un congé actif sur cette période.');
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
                Mail::to($this->grh->email)->send(new DemandeCongeMail(Auth::user(), $demande));
                Notification::send($this->grh, new DemandeCongeNotification($demande));
                $demande->date_soumission = now();
                $demande->update();
                return redirect()->route('dashboard')->with('message', 'Demande absence/congé mise à jour avec succès');
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Une erreur est survenue. Envoie du mail echoué');
            }
        } else {
            $demande->update();
            return redirect()->route('dashboard')->with('message', 'Demande absence/congé mise à jour avec succès');
        }
    }

    public function changerStatut(DemandeConge $demande)
    {
        // Vérifier si une demande de congé existe déjà pour cette période
        $chevauchement = DemandeConge::where('employe_id', $demande->employe_id)
            ->where(function ($query) use ($demande) {
                $query->whereBetween('date_debut', [$demande->date_debut, $demande->date_fin])
                    ->orWhereBetween('date_fin', [$demande->date_debut, $demande->date_fin])
                    ->orWhere(function ($query) use ($demande) {
                        $query->where('date_debut', '<=', $demande->date_debut)
                            ->where('date_fin', '>=', $demande->date_fin);
                    });
            })
            ->whereIn('statut_demande_id', [$this->statutAttente->id, $this->statutAccepter->id]) // Seulement les demandes actives ou en attente
            ->exists();

        if ($chevauchement) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une demande de congé ou un congé actif sur cette période.');
        }

        try {
            Mail::to($this->grh->email)->send(new DemandeCongeMail($this->user, $demande));
            Notification::send($this->grh, new DemandeCongeNotification($demande));

            $demande->statut_demande_id = $this->statutAttente->id;
            $demande->date_soumission = now();
            $demande->update();

            return redirect()->route('dashboard')->with('message', 'Une demande a changé de statut (planifiée)');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue. Envoie du mail echoué');
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
        // Mail::to($grh->email)->send(new DemandeCongeMail(Auth::user(), $demande));
        Notification::send($grh, new ModificationDemandeNotification($demande, Auth::user()));
        return redirect()->route('dashboard')->with('message', 'Demande de modification envoyée avec succès');
    }


    // **********************************************************************
    // Gestion des demandes des employes
    public function accepterDemande($id)
    {
        $demande = DemandeConge::findOrFail($id);
        $demande->update(['statut_demande_id' => $this->statutAccepter->id]);
        try {
            Mail::to($demande->employe->user->email)->send(new ResponseDemandeMail($demande->employe->user, $demande, 'acceptée'));
            Notification::send($demande->employe->user, new DemandeCongeNotification($demande, 'acceptée'));
            return redirect()->route('dashboard')->with('message', 'Demande acceptée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors de l\'envoi du mail : ' . $e->getMessage());
        }
    }

    public function refuserDemande($id)
    {
        $demande = DemandeConge::findOrFail($id);
        $demande->update(['statut_demande_id' => $this->statutRefuser->id]);
        try {
            Mail::to($demande->employe->user->email)->send(new ResponseDemandeMail($demande->employe->user, $demande, 'refusée'));
            Notification::send($demande->employe->user, new DemandeCongeNotification($demande, 'refusée'));
            return redirect()->route('dashboard')->with('message', 'Demande refusée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors de l\'envoi du mail : ' . $e->getMessage());
        }
    }

    public function VoirDemande(DemandeConge $demande)
    {
        //Employe
        $user = $demande->employe->user;
        $this->nom = $user->nom;
        $this->prenom = $user->prenom;
        $this->email = $user->email;
        $this->telephone = $user->telephone;
        $this->adresse = $user->adresse;
        $this->fonctionShow = $user->fonction->nom;
        $this->adresse = $user->adresse;
        $this->dateNaissance = $user->date_naissance;
        $this->dateEmbauche = $user->date_embauche;
        $this->matricule = $user->matricule;
        $this->sexe = $user->sexe;
        $this->roleShow = $user->role;
        $this->serviceShow = $user->service->nom;

        //Demande
        $this->dateDebut = $demande->date_debut;
        $this->dateFin = $demande->date_fin;
        $this->motif = $demande->motif;
        $this->typeConge = $demande->type_conge;
        $this->dateSoumission = $demande->date_soumission;
    }

    public function render()
    {
        return view('livewire.dashboard.responsable');
    }
}
