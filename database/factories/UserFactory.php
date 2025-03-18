<?php

namespace Database\Factories;

use App\Models\Fonction;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matricule' => 123456789,
            'nom' => 'grh',
            'prenom' => 'grh',
            'email' => 'andersontiemtore87@gmail.com',
            'telephone' => 56145852,
            'sexe' => 'masculin',
            'adresse' => 'Ouagadougou',
            'date_naissance' => '1995-12-12',
            'date_embauche' => '2025-01-01',
            'password' => Hash::make('password'),
            'role' => 'grh',
            'service_id' => Service::query()->first()->id,
            'fonction_id' => Fonction::query()->first()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
