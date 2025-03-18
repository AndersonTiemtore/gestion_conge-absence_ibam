<?php

namespace App\Livewire\Responsable;

use App\Models\Fonction;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{


    public User $user;
    public $services;
    public $fonctions;

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

    public function rules()
    {
        return [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'matricule' => [
                'required',
                Rule::unique('users', 'matricule')->ignore($this->user->id),
            ],
            'dateNaissance' => 'required|',
            'dateEmbauche' => 'required|',
            'sexe' => 'required|in:masculin,feminin',
            'service' => 'required|exists:services,id',
            'fonction' => 'required|exists:fonctions,id',
            'telephone' => 'required|',
            'adresse' => 'required|',
        ];
    }


    #[On('userToEdit')]
    public function userToEdit($id)
    {
        $this->user = User::query()->findOrFail($id);
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->telephone = $this->user->telephone;
        $this->adresse = $this->user->adresse;
        $this->fonction = $this->user->fonction_id;
        $this->adresse = $this->user->adresse;
        $this->dateNaissance = $this->user->date_naissance;
        $this->dateEmbauche = $this->user->date_embauche;
        $this->matricule = $this->user->matricule;
        $this->sexe = $this->user->sexe;
        $this->service = $this->user->service_id;

        $this->services = Service::all();
        $this->fonctions = Fonction::query()->where('nom', '!=', 'Administrateur')->get();

        $this->dispatch('update-event');
    }


    public function getServices()
    {
        return $this->services;
    }

    public function getFonctions()
    {
        return $this->fonctions;
    }

    public function update()
    {

        $this->validate();
        $responsableExistant = User::query()
            ->where('role', 'responsable')
            ->where('service_id', $this->service)
            ->where('id', '!=', $this->user->id)
            ->first();

        if ($responsableExistant) {
            session()->flash('error', 'Un responsable existe déjà pour ce service.');
        } else {
            $this->validate();
            $this->user->nom = $this->nom;
            $this->user->prenom = $this->prenom;
            $this->user->email = $this->email;
            $this->user->telephone = $this->telephone;
            $this->user->matricule = $this->matricule;
            $this->user->service_id = $this->service;
            $this->user->fonction_id = $this->fonction;
            $this->user->adresse = $this->adresse;
            $this->user->date_naissance = $this->dateNaissance;
            $this->user->date_embauche = $this->dateEmbauche;
            $this->user->adresse = $this->adresse;
            $this->user->sexe = $this->sexe;
            $this->user->update();

            // Redirection avec un message de succès
            redirect()->route('responsables')->with('message', 'Responsable ' . $this->nom . ' modifié avec succès');
        }
    }

    public function render()
    {
        return view('livewire.responsable.update');
    }
}
