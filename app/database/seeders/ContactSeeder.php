<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            ['full_name' => 'H. Ahmad Dahlan', 'phone' => '081234567890', 'organization_name' => 'PRM Wage', 'organization_type' => 'PRM', 'position' => 'Ketua'],
            ['full_name' => 'Dra. Siti Walidah', 'phone' => '082345678901', 'organization_name' => 'PRA Wage', 'organization_type' => 'PRA', 'position' => 'Ketua'],
            ['full_name' => 'Ir. Budi Setiawan', 'phone' => '083456789012', 'organization_name' => 'PCM Taman', 'organization_type' => 'PCM', 'position' => 'Wakil Ketua'],
            ['full_name' => 'Dr. Fajar Rahman', 'phone' => '084567890123', 'organization_name' => 'ICMI', 'organization_type' => 'ICMI', 'position' => 'Anggota'],
            ['full_name' => 'Hj. Aisyah Nur', 'phone' => '085678901234', 'organization_name' => 'Majelis Tabligh', 'organization_type' => 'Tabligh', 'position' => 'Sekretaris'],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
