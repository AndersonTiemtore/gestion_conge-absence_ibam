<?php

namespace Database\Seeders;

// database/seeders/ServiceSeeder.php
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::create(['nom' => 'Service 1']);
        Service::create(['nom' => 'Service 2']);
    }
}
