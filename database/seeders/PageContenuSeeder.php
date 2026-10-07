<?php

namespace Database\Seeders;

use App\Models\PageContenu;
use Illuminate\Database\Seeder;

class PageContenuSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'apropos',
                'titre' => 'À propos de KF Business',
                'contenu' => '<h2>Qui sommes-nous?</h2><p>KF Business est une plateforme d\'investissement collaborative...</p>',
                'meta_description' => 'En savoir plus sur KF Business et notre mission',
                'meta_keywords' => 'KF Business, investissement, Senegal',
                'published' => true,
                'published_at' => now(),
            ],
            [
                'slug' => 'mentions-legales',
                'titre' => 'Mentions légales',
                'contenu' => '<h2>Mentions légales</h2><p>KF Business Company International SARL...</p>',
                'meta_description' => 'Mentions légales - KF Business',
                'meta_keywords' => 'mentions, legales',
                'published' => true,
                'published_at' => now(),
            ],
            [
                'slug' => 'politique-confidentialite',
                'titre' => 'Politique de confidentialité',
                'contenu' => '<h2>Protection de vos données</h2><p>KF Business s\'engage à protéger vos données...</p>',
                'meta_description' => 'Politique de confidentialité - KF Business',
                'meta_keywords' => 'confidentialité, données, privacy',
                'published' => true,
                'published_at' => now(),
            ],
            [
                'slug' => 'conditions-generales',
                'titre' => 'Conditions générales d\'utilisation',
                'contenu' => '<h2>Conditions générales</h2><p>En utilisant KF Business, vous acceptez les conditions...</p>',
                'meta_description' => 'Conditions générales d\'utilisation - KF Business',
                'meta_keywords' => 'conditions, CGU, termes',
                'published' => true,
                'published_at' => now(),
            ],
            [
                'slug' => 'faq',
                'titre' => 'Questions fréquemment posées',
                'contenu' => '<h2>FAQ</h2><p><strong>Q: Comment fonctionne KF Business?</strong></p><p>R: Notre plateforme vous permet d\'investir dans des projets...</p>',
                'meta_description' => 'Questions fréquemment posées - KF Business',
                'meta_keywords' => 'FAQ, questions, aide',
                'published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($pages as $page) {
            PageContenu::create($page);
        }

        $this->command->info('Pages de contenu créées avec succès !');
    }
}
