<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\NotificationController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_marks_notification_as_read()
    {
        $user = User::factory()->create();
        
        // Créer une notification manuellement
        $notification = $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ExampleNotification',
            'data' => [],
            'read_at' => null,
        ]);
        
        // Authentifier l'utilisateur
        $this->actingAs($user);
        
        // Simuler une requête POST pour marquer la notification comme lue
        $response = $this->post("/notifications/{$notification->id}/mark-as-read");
        
        // Vérifier que la notification a été marquée comme lue
        $this->assertNotNull($notification->fresh()->read_at);
    }

    #[Test]
    public function it_handles_nonexistent_notification()
    {
        $user = User::factory()->create();
        
        // Authentifier l'utilisateur
        $this->actingAs($user);
        
        // Simuler une requête POST pour une notification inexistante
        $response = $this->post("/notifications/nonexistent-id/mark-as-read");
        
        // Vérifier que la réponse est une redirection
        $response->assertRedirect();
    }
}