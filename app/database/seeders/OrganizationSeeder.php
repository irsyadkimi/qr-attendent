<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $orgs = [
            ['name' => 'PRM Wage', 'type' => 'PRM'],
            ['name' => 'PCM Taman', 'type' => 'PCM'],
            ['name' => 'PDM Madiun', 'type' => 'PDM'],
            ['name' => 'ICMI', 'type' => 'ICMI'],
            ['name' => 'Majelis Tabligh', 'type' => 'Tabligh'],
            ['name' => 'ISM Wage', 'type' => 'ISM'],
            ['name' => 'Majelis Ekonomi', 'type' => 'Ekonomi'],
            ['name' => 'BTM Wage', 'type' => 'BTM'],
        ];

        foreach ($orgs as $org) {
            Organization::create($org);
        }
    }
}
