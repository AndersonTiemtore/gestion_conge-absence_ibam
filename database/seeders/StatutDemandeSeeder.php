<?php

namespace Database\Seeders;

use App\Models\StatutDemande;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatutDemandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $statuts = ['plannifier', 'demander', 'refuser', 'accepter'];

        foreach ($statuts as $statut) {
            StatutDemande::factory()->create([
                'statut' => $statut,
            ]);
        }
    }
}
