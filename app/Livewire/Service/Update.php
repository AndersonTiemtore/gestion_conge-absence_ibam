<?php

namespace App\Livewire\Service;

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    public Service $service;

    public $nom = '';
    public $description = '';

    public function rules()
    {
        return [
            'nom' => 'required|string',
        ];
    }

    #[On('serviceToEdit')]
    public function serviceToEdit($id)
    {
        $this->service = Service::query()->findOrFail($id);
        $this->nom = $this->service->nom;
        $this->description = $this->service->description;

        $this->dispatch('update-event');
    }


    public function update()
    {
        $this->validate();
        $this->service->nom = $this->nom;
        $this->service->description = $this->description;
        $this->service->update();

        redirect()->route('services')->with('message', 'Service ' . $this->nom . ' modifié avec succès');
    }

    public function render()
    {
        return view('livewire.service.update');
    }
}
