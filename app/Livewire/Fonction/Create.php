<?php

namespace App\Livewire\Fonction;

use App\Models\Fonction;
use Livewire\Component;

class Create extends Component
{

    public $nom = '';
    public $description = '';


    protected $rules = [
        'nom' => 'required|min:3|max:50',
    ];

    public function submit()
    {
        $this->validate();
        $fonction = new Fonction();
        $fonction->nom = $this->nom;
        $fonction->description = $this->description;
        $fonction->save();

        return redirect()->route('fonctions')->with('message', 'Fonction ' . $this->nom . ' ajoutée avec succès');
    }

    public function render()
    {
        return view('livewire.fonction.create');
    }
}
