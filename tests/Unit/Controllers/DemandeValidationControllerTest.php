<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\DemandeValidationController;
use App\Mail\ResponseDemandeMail;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\StatutDemande;
use App\Models\User;
use App\Notifications\DemandeCongeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DemandeValidationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_accepts_demande_conge()
    {
        Mail::fake();
        Notification::fake();
        
        $user = User::factory()->create();
        $employe = Employe::factory()->create(['user_id' => $user->id]);
        
        // Créer les statuts avec des valeurs valides
        $statutEnAttente = StatutDemande::factory()->create(['statut' => 'en attente']);
        $statutAccepter = StatutDemande::factory()->create(['statut' => 'accepté']);
        
        $demande = DemandeConge::factory()->create([
            'employe_id' => $employe->id,
            'statut_demande_id' => $statutEnAttente->id
        ]);
        
        // Simuler une requête HTTP pour accepter la demande
        $response = $this->post("/demandes/{$demande->id}/accepter");
        
        // Vérifier que le statut a été mis à jour
        $this->assertEquals($statutAccepter->id, $demande->fresh()->statut_demande_id);
        
        // Vérifier que l'e-mail a été envoyé
        Mail::assertSent(ResponseDemandeMail::class, function ($mail) use ($user, $demande) {
            return $mail->hasTo($user->email);
        });
        
        // Vérifier que la notification a été envoyée
        Notification::assertSentTo(
            $user,
            DemandeCongeNotification::class,
            function ($notification, $channels) use ($demande) {
                return $notification->demande->id === $demande->id;
            }
        );
    }

    #[Test]
    public function it_refuses_demande_conge()
    {
        Mail::fake();
        Notification::fake();
        
        $user = User::factory()->create();
        $employe = Employe::factory()->create(['user_id' => $user->id]);
        
        // Créer les statuts avec des valeurs valides
        $statutEnAttente = StatutDemande::factory()->create(['statut' => 'en attente']);
        $statutRefuser = StatutDemande::factory()->create(['statut' => 'refusé']); // Correction : 'refusé'
        
        $demande = DemandeConge::factory()->create([
            'employe_id' => $employe->id,
            'statut_demande_id' => $statutEnAttente->id
        ]);
        
        // Simuler une requête HTTP pour refuser la demande
        $response = $this->post("/demandes/{$demande->id}/refuser");
        
        // Vérifier que le statut a été mis à jour
        $this->assertEquals($statutRefuser->id, $demande->fresh()->statut_demande_id);
        
        // Vérifier que l'e-mail a été envoyé
        Mail::assertSent(ResponseDemandeMail::class, function ($mail) use ($user, $demande) {
            return $mail->hasTo($user->email);
        });
        
        // Vérifier que la notification a été envoyée
        Notification::assertSentTo(
            $user,
            DemandeCongeNotification::class,
            function ($notification, $channels) use ($demande) {
                return $notification->demande->id === $demande->id;
            }
        );
    }
}