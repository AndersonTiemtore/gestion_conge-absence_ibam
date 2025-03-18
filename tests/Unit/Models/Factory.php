<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Service;
use App\Models\Fonction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'service_id' => Service::factory(),
            'fonction_id' => Fonction::factory(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->department(),
            'description' => $this->faker->sentence(),
        ];
    }
}

class FonctionFactory extends Factory
{
    protected $model = Fonction::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->unique()->jobTitle(),
            'description' => $this->faker->sentence(),
        ];
    }
}

class EmployeFactory extends Factory
{
    protected $model = Employe::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'matricule' => $this->faker->unique()->numerify('EMP-####'),
            'date_embauche' => $this->faker->date(),
        ];
    }
}

class GrhFactory extends Factory
{
    protected $model = Grh::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'niveau_acces' => $this->faker->randomElement(['niveau1', 'niveau2', 'niveau3']),
        ];
    }
}

class ResponsableFactory extends Factory
{
    protected $model = Responsable::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'service_id' => Service::factory(),
        ];
    }
}

class DemandeCongeFactory extends Factory
{
    protected $model = DemandeConge::class;

    public function definition(): array
    {
        return [
            'employe_id' => Employe::factory(),
            'statut_demande_id' => StatutDemande::factory(),
            'date_debut' => $this->faker->dateTimeBetween('now', '+2 months'),
            'date_fin' => $this->faker->dateTimeBetween('+2 months', '+3 months'),
            'motif' => $this->faker->sentence(),
        ];
    }
}

class StatutDemandeFactory extends Factory
{
    protected $model = StatutDemande::class;

    public function definition(): array
    {
        return [
            'statut' => $this->faker->randomElement(['En attente', 'Approuvée', 'Refusée', 'Annulée']),
        ];
    }
}