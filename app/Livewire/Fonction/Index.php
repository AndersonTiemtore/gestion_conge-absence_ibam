<?php

namespace App\Livewire\Fonction;

use App\Models\Fonction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;

    public function deleteService($id)
    {
        $fonction = Fonction::query()->findOrFail($id);
        $fonction->delete();
    }

    public function sendFonction($id)
    {
        $this->dispatch('fonctionToEdit', id: $id)->to(Update::class);
    }

    public function render()
    {
        $fonctions = Fonction::query()
            ->where('nom', '!=', 'Administrateur')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);
        return view('livewire.fonction.index', compact('fonctions'));
    }
}
