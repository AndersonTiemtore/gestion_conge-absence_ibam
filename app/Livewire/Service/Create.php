<?php

namespace App\Livewire\Service;

use App\Models\Service;
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
        $service = new Service();
        $service->nom = $this->nom;
        $service->description = $this->description;
        $service->save();

        return redirect()->route('services')->with('message', 'Service ' . $this->nom . ' ajouté avec succès');
    }

    public function render()
    {
        return view('livewire.service.create');
    }
}
