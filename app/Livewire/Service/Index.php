<?php

namespace App\Livewire\Service;

use App\Models\Service;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search;

    public function deleteService($id)
    {
        $service = Service::query()->findOrFail($id);
        $service->delete();
    }

    public function sendService($id)
    {
        $this->dispatch('serviceToEdit', id: $id)->to(Update::class);
    }

    public function render()
    {
        $services = Service::query()
            // Ajouter une condition pour filtrer par nom ou prénom
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        return view('livewire.service.index', compact('services'));
    }
}
