<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AuthentificationController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthentificationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_login_form_view()
    {
        $controller = new AuthentificationController();
        $view = $controller->loginForm();
        
        $this->assertEquals('authentication.login', $view->name()); // Correction du nom de la vue
        $this->assertArrayHasKey('pageConfigs', $view->getData());
        $this->assertEquals(['myLayout' => 'blank'], $view->getData()['pageConfigs']);
    }

    #[Test]
    public function it_authenticates_valid_user()
    {
        $user = User::factory()->create([
            'email' => 'andersontiemtore87@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Simuler une requête POST vers /login
        $response = $this->post('/login', [
            'email' => 'andersontiemtore87@gmail.com',
            'password' => 'password',
            'remember' => true,
        ]);

        // Vérifier que l'utilisateur est authentifié
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function it_returns_password_forgotten_form_view()
    {
        $controller = new AuthentificationController();
        $view = $controller->passwordForgottenForm();
        
        $this->assertEquals('authentication.passwordForgotten', $view->name()); // Correction du nom de la vue
        $this->assertArrayHasKey('pageConfigs', $view->getData());
    }

    #[Test]
    public function it_returns_reset_password_form_view()
    {
        $token = 'test-token';
        $controller = new AuthentificationController();
        $view = $controller->resetPasswordForm($token);
        
        $this->assertEquals('authentication.resetPassword', $view->name()); // Correction du nom de la vue
        $this->assertArrayHasKey('token', $view->getData());
        $this->assertEquals($token, $view->getData()['token']);
    }

    #[Test]
    public function it_logs_out_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        // Simuler une requête POST vers /logout
        $response = $this->post('/logout');

        // Vérifier que l'utilisateur est déconnecté
        $this->assertGuest();
    }

    #[Test]
    public function it_returns_change_password_form_for_default_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        
        // Authentifier l'utilisateur
        $this->actingAs($user);
        
        // Accéder au formulaire de changement de mot de passe
        $response = $this->get('/change-password');
        
        // Vérifier que la vue retournée est correcte
        $response->assertViewIs('authentication.changePassword'); // Correction du nom de la vue
    }
}