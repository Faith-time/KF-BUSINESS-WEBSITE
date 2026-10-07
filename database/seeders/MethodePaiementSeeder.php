<?php

namespace Database\Seeders;

use App\Models\MethodePaiement;
use Illuminate\Database\Seeder;

class MethodePaiementSeeder extends Seeder
{
    public function run(): void
    {
        $methodes = [
            [
                'nom' => 'Wave',
                'description' => 'Portefeuille mobile Wave Money',
                'code' => 'wave',
                'active' => true,
                'frais_pourcentage' => 1.5,
                'frais_fixes' => 25,
                'icon' => 'Smartphone',
                'config' => [
                    'api_key' => env('WAVE_API_KEY'),
                    'api_url' => 'https://api.wave.money',
                ],
            ],
            [
                'nom' => 'MTN Money',
                'description' => 'Portefeuille MTN Money Senegal',
                'code' => 'mtn',
                'active' => true,
                'frais_pourcentage' => 1.0,
                'frais_fixes' => 20,
                'icon' => 'Phone',
                'config' => [
                    'api_key' => env('MTN_API_KEY'),
                    'api_url' => 'https://api.mtn.sn',
                ],
            ],
            [
                'nom' => 'Orange Money',
                'description' => 'Portefeuille Orange Money Senegal',
                'code' => 'orange',
                'active' => true,
                'frais_pourcentage' => 1.2,
                'frais_fixes' => 25,
                'icon' => 'Smartphone',
                'config' => [
                    'api_key' => env('ORANGE_API_KEY'),
                    'api_url' => 'https://api.orangemoney.sn',
                ],
            ],
            [
                'nom' => 'Virement bancaire',
                'description' => 'Virement bancaire national',
                'code' => 'bank_transfer',
                'active' => true,
                'frais_pourcentage' => 0.5,
                'frais_fixes' => 0,
                'icon' => 'Building2',
                'config' => [],
            ],
            [
                'nom' => 'Chèque',
                'description' => 'Paiement par chèque',
                'code' => 'cheque',
                'active' => false,
                'frais_pourcentage' => 0.0,
                'frais_fixes' => 0,
                'icon' => 'FileText',
                'config' => [],
            ],
        ];

        foreach ($methodes as $methode) {
            MethodePaiement::create($methode);
        }

        $this->command->info('Méthodes de paiement créées avec succès !');
    }
}
