<?php

namespace App\Livewire\Fonction;

use App\Models\Fonction;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{

    public Fonction $fonction;

    public $nom = '';
    public $description = '';

    public function rules()
    {
        return [
            'nom' => 'required|string',
        ];
    }

    #[On('fonctionToEdit')]
    public function fonctionToEdit($id)
    {
        $this->fonction = Fonction::query()->findOrFail($id);
        $this->nom = $this->fonction->nom;
        $this->description = $this->fonction->description;

        $this->dispatch('update-event');
    }


    public function update()
    {
        $this->validate();
        $this->fonction->nom = $this->nom;
        $this->fonction->description = $this->description;
        $this->fonction->update();

        redirect()->route('fonctions')->with('message', 'Fonction ' . $this->nom . ' modifiée avec succès');
    }
    public function render()
    {
        return view('livewire.fonction.update');
    }
}
