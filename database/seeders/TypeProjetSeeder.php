<?php

namespace Database\Seeders;

use App\Models\TypeProjet;
use Illuminate\Database\Seeder;

class TypeProjetSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'nom' => 'Agriculture',
                'description' => 'Projets agricoles et agroalimentaires',
                'icon' => 'Sprout',
                'active' => true,
            ],
            [
                'nom' => 'Aviculture',
                'description' => 'Élevage de volailles et production d\'œufs',
                'icon' => 'Bird2',
                'active' => true,
            ],
            [
                'nom' => 'Pisciculture',
                'description' => 'Élevage de poissons et aquaculture',
                'icon' => 'Fish',
                'active' => true,
            ],
            [
                'nom' => 'Commerce',
                'description' => 'Projets commerciaux et de retail',
                'icon' => 'ShoppingCart',
                'active' => true,
            ],
            [
                'nom' => 'Technologie',
                'description' => 'Startups et projets technologiques',
                'icon' => 'Code2',
                'active' => true,
            ],
            [
                'nom' => 'Énergie renouvelable',
                'description' => 'Projets d\'énergie solaire, éolienne et bioénergie',
                'icon' => 'Sun',
                'active' => true,
            ],
            [
                'nom' => 'Infrastructure',
                'description' => 'Routes, eau, électricité et télécommunications',
                'icon' => 'Building2',
                'active' => true,
            ],
            [
                'nom' => 'Tourisme',
                'description' => 'Hôtellerie, restauration et loisirs',
                'icon' => 'MapPin',
                'active' => true,
            ],
            [
                'nom' => 'Immobilier',
                'description' => 'Projets immobiliers et construction',
                'icon' => 'Home',
                'active' => true,
            ],
            [
                'nom' => 'Éducation',
                'description' => 'Écoles, formations et édtech',
                'icon' => 'BookOpen',
                'active' => true,
            ],
        ];

        foreach ($types as $type) {
            TypeProjet::create($type);
        }

        $this->command->info('Types de projets créés avec succès !');
    }
}
