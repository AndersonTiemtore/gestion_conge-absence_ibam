<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{
    public $user;
    public $matricule;
    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $role = '';
    public $sexe = '';
    public $adresse = '';
    public $dateNaissance = '';
    public $dateEmbauche = '';
    public $fonction = '';
    public $service = '';

    public $telephone = '';

    public $hasDeleted = false;


    public function rules()
    {
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'adresse' => 'required',
            'prenom' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'telephone' => 'required',
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->matricule = $this->user->matricule;
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->sexe = $this->user->sexe;
        $this->telephone = $this->user->telephone;
        $this->role = $this->user->role;
        $this->dateNaissance = $this->user->date_naissance;
        $this->dateEmbauche = $this->user->date_embauche;
        $this->adresse = $this->user->adresse;
        $this->fonction = $this->user->fonction->nom;
        $this->service = $this->user->service->nom;
        $this->role = $this->user->role;
    }

    public function update()
    {
        $this->validate();
        $this->user->nom = $this->nom;
        $this->user->prenom = $this->prenom;
        $this->user->email = $this->email;
        $this->user->telephone = $this->telephone;
        $this->user->adresse = $this->adresse;
        $this->user->update();

        return redirect()->route('profile')->with('message', 'Profile mis à jour avec succès');
    }

    public function reinitialiser()
    {
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->telephone = $this->user->telephone;
        $this->adresse = $this->user->adresse;
    }

    // public function deleteAccount()
    // {
    //     $this->user->delete();
    //     redirect()->route('login')->with('success', 'Compte supprimé avec succès');
    // }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
