<?php

namespace Tests\Integration\Models;

use App\Models\User;
use App\Models\Service;
use App\Models\Employe;
use App\Models\Grh;
use App\Models\Responsable;
use App\Models\Fonction;
use App\Models\DemandeConge;
use App\Models\StatutDemande;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_employe_relationship()
    {
        // Créer un utilisateur et un employé associé
        $user = User::factory()->create();
        $employe = Employe::factory()->create(['user_id' => $user->id]);

        // Vérifier que la relation User->employe fonctionne
        $this->assertEquals($employe->id, $user->employe->id);
        
        // Vérifier que la relation Employe->user fonctionne
        $this->assertEquals($user->id, $employe->user->id);
    }

    /** @test */
    public function user_service_relationship()
    {
        // Créer un service et un utilisateur associé
        $service = Service::factory()->create();
        $user = User::factory()->create(['service_id' => $service->id]);

        // Vérifier que la relation User->service fonctionne
        $this->assertEquals($service->id, $user->service->id);
        
        // Vérifier que la relation Service->users fonctionne
        $this->assertTrue($service->users->contains($user));
    }

    /** @test */
    public function user_fonction_relationship()
    {
        // Créer une fonction et un utilisateur associé
        $fonction = Fonction::factory()->create();
        $user = User::factory()->create(['fonction_id' => $fonction->id]);

        // Vérifier que la relation User->fonction fonctionne
        $this->assertEquals($fonction->id, $user->fonction->id);
        
        // Vérifier que la relation Fonction->users fonctionne
        $this->assertTrue($fonction->users->contains($user));
    }

    /** @test */
    public function user_grh_relationship()
    {
        // Créer un utilisateur et un GRH associé
        $user = User::factory()->create();
        $grh = Grh::factory()->create(['user_id' => $user->id]);

        // Vérifier que la relation User->grh fonctionne
        $this->assertEquals($grh->id, $user->grh->id);
        
        // Vérifier que la relation Grh->user fonctionne
        $this->assertEquals($user->id, $grh->user->id);
    }

    /** @test */
    public function employe_demandes_relationship()
    {
        // Créer un employé et des demandes associées
        $employe = Employe::factory()->create();
        $statut = StatutDemande::factory()->create();
        
        $demande1 = DemandeConge::factory()->create([
            'employe_id' => $employe->id,
            'statut_demande_id' => $statut->id
        ]);
        
        $demande2 = DemandeConge::factory()->create([
            'employe_id' => $employe->id,
            'statut_demande_id' => $statut->id
        ]);

        // Vérifier que la relation Employe->demandes fonctionne
        $this->assertTrue($employe->demandes->contains($demande1));
        $this->assertTrue($employe->demandes->contains($demande2));
        $this->assertEquals(2, $employe->demandes->count());
        
        // Vérifier que la relation DemandeConge->employe fonctionne
        $this->assertEquals($employe->id, $demande1->employe->id);
        $this->assertEquals($employe->id, $demande2->employe->id);
    }

    /** @test */
    public function demande_statut_relationship()
    {
        // Créer un statut et des demandes associées
        $statut = StatutDemande::factory()->create(['statut' => 'En attente']);
        $employe = Employe::factory()->create();
        
        $demande = DemandeConge::factory()->create([
            'employe_id' => $employe->id,
            'statut_demande_id' => $statut->id
        ]);

        // Vérifier que la relation DemandeConge->statutDemande fonctionne
        $this->assertEquals($statut->id, $demande->statutDemande->id);
        $this->assertEquals('En attente', $demande->statutDemande->statut);
        
        // Vérifier que la relation StatutDemande->demandes fonctionne
        $this->assertTrue($statut->demandes->contains($demande));
    }
}