<?php

namespace Database\Seeders;

// database/seeders/FonctionSeeder.php
use App\Models\Fonction;
use Illuminate\Database\Seeder;

class FonctionSeeder extends Seeder
{
    public function run()
    {
        Fonction::create(['nom' => 'Fonction 1']);
        Fonction::create(['nom' => 'Fonction 2']);
    }
}
