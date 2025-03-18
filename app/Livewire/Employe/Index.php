<?php

namespace App\Livewire\Employe;

use App\Models\Fonction;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $employes;


    use WithPagination;

    public $services;
    public $service;
    public $fonctions;
    public $fonction;
    public $niveaux;
    public $niveau;
    public $search;
    public $role;


    //
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

    public function mount()
    {
        $this->services = Service::all();
        $this->fonctions = Fonction::query()->where('nom', '!=', 'Administrateur')->get();
    }

    public function deleteEmploye($id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
    }

    public function sendUser($id)
    {
        $this->dispatch('userToEdit', id: $id)->to(Update::class);
    }

    public function sendResponsable($id)
    {
        $this->dispatch('userToEdit', id: $id)->to(Update::class);
    }

    public function showEmploye(User $user)
    {
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
    }

    public function render()
    {
        $users = User::query()
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            }, function ($query) {
                $query->whereIn('role', ['employe', 'responsable']);
            })
            ->whereHas('service', function ($query) {
                $query->when($this->service, function ($query) {
                    $query->where('service_id', $this->service);
                });
            })
            ->whereHas('fonction', function ($query) {
                $query->when($this->fonction, function ($query) {
                    $query->where('fonction_id', $this->fonction);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%');
                });
            })
            // ->where('id', '!=', Auth::user()->id) // Décommentez si besoin
            ->paginate(10);

        return view('livewire.employe.index', compact('users'));
    }
}
