<?php

namespace App\Livewire\Responsable;

use App\Models\Employe;
use App\Models\Fonction;
use App\Models\Responsable;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public $matricule = '';
    public $nom = '';
    public $prenom = '';
    public $adresse = '';
    public $email = '';
    public $telephone = '';
    public $dateNaissance = '';
    public $dateEmbauche = '';
    public $sexe = '';
    public $service = '';
    public $fonction;
    public $services;
    public $fonctions;

    protected $rules = [
        'matricule' => 'required|',
        'nom' => 'required|',
        'prenom' => 'required|',
        'telephone' => 'required|',
        'adresse' => 'required|',
        'dateNaissance' => 'required|',
        'dateEmbauche' => 'required|',
        'sexe' => 'required|in:masculin,feminin',
        'email' => 'required|unique:users,email',
        'service' => 'required|exists:services,id',
        'fonction' => 'required|exists:fonctions,id',

    ];

    public function mount()
    {
        $this->services = Service::all();
        $this->fonctions = Fonction::query()->where('nom', '!=', 'Administrateur')->get();
    }

    public function submit()
    {
        $this->validate();

        if (User::query()->where(['role' => 'responsable', 'service_id' => $this->service])->first()) {
            session()->flash('error', 'Un responsable existe deja pour ce service');
        } else {
            $user = new User();
            $user->matricule = $this->matricule;
            $user->nom = $this->nom;
            $user->prenom = $this->prenom;
            $user->email = $this->email;
            $user->telephone = $this->telephone;
            $user->date_naissance = $this->dateNaissance;
            $user->date_embauche = $this->dateEmbauche;
            $user->sexe = $this->sexe;
            $user->adresse = $this->adresse;
            $user->role = 'responsable';
            $user->password = Hash::make('password');
            $user->service_id = $this->service;
            $user->fonction_id = $this->fonction;
            $user->save();

            $employe = new Employe();
            $employe->user_id = $user->id;
            $employe->save();

            redirect()->route('employes')->with('message', 'Responsable ' . $this->nom . ' ajouté avec succès');
        }
    }

    public function render()
    {
        return view('livewire.responsable.create');
    }
}
