<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. RÔLES (IMPORTANT: en premier)
        $this->call(RoleSeeder::class);

        // 2. DONNÉES DE BASE
        $this->call(TypeProjetSeeder::class);
        $this->call(MethodePaiementSeeder::class);
        $this->call(PageContenuSeeder::class);  // UNE SEULE FOIS ✅
        $this->call(ProjetSeeder::class);  // UNE SEULE FOIS ✅

        // 3. UTILISATEURS DE TEST
        User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
        ])->assignRole('administrateur');

        User::factory()->create([
            'name' => 'Comptable Test',
            'email' => 'comptable@example.com',
        ])->assignRole('comptable');


        User::factory()->create([
            'name' => 'Investisseur Test',
            'email' => 'investisseur@example.com',
        ])->assignRole('investisseur');

        $this->command->info('✅ Base de données seedée !');
    }
}
