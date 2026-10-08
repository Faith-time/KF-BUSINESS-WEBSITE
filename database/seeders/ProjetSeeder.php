<?php

namespace Database\Seeders;

use App\Models\Projet;
use App\Models\TypeProjet;
use App\Models\User;
use App\Models\TicketInvestissement;
use App\Models\RepartitionBudget;
use App\Models\ProjectionFinanciere;
use App\Models\IndicateurResultat;
use App\Enums\StatutProjet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjetSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer un promoteur
        $promoteur = User::where('email', 'promoteur@example.com')->first()
            ?? User::factory()->create(['name' => 'Promoteur Test', 'email' => 'promoteur@example.com']);

        $projets = [
            [
                'nom' => 'Ferme Avicole 5 000 Pondeuses',
                'slug' => 'ferme-avicole-5000-pondeuses',
                'description_courte' => 'Production d\'œufs de poules pondeuses pour le marché sénégalais. Un projet de 5 000 pondeuses en système semi-intensif.',
                'description' => 'Projet de ferme avicole de haute capacité établie à Thiès, Sénégal. La ferme comptera 5 000 poules pondeuses en système semi-intensif, destinées à une production d\'œufs de qualité supérieure. Avec une vision à long terme, le projet vise l\'autosuffisance progressive en aliments et la commercialisation de produits secondaires. Les revenus seront générés par la vente d\'œufs, le fumier pour les cultures biologiques, et potentiellement l\'élevage de poules de réforme.',
                'type_projet_id' => TypeProjet::where('nom', 'Aviculture')->first()?->id ?? 1,
                'promoteur_id' => $promoteur->id,
                'localisation' => 'Thiès, Sénégal',
                'montant_total' => 300_000_000,
                'montant_collecte' => 180_000_000,
                'montant_min_investissement' => 100_000,
                'duree_mois' => 36,
                'taux_rendement_annuel' => 18,
                'statut' => StatutProjet::FINANCEMENT,
                'date_debut' => now()->addMonth(),
                'date_fin' => now()->addMonths(3),
                'date_fermeture_collecte' => now()->addWeeks(2),
                'image_hero' => 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg?auto=compress&cs=tinysrgb&w=800',
                'pourcentage_completion' => 60,
                'risques' => 'Volatilité des prix des aliments, maladies aviaires, fluctuations du marché des œufs.',
                'opportunites' => 'Croissance de la demande d\'œufs, potentiel de valeur ajoutée, expansion vers d\'autres produits avicoles.',
                'featured' => true,
                'visible' => true,
            ],
            [
                'nom' => 'Culture Maraîchère Industrielle',
                'slug' => 'culture-maraichere-industrielle',
                'description_courte' => 'Production de légumes irrigués sur 10 hectares à Saint-Louis. Destinée aux marchés urbains.',
                'description' => 'Projet agricole de maraîchage industriel situé à Saint-Louis, Sénégal. Le projet couvre 10 hectares de terres irriguées avec un système de goutte-à-goutte moderne pour la production de légumes (tomates, laitues, poivrons, oignons). La production est destinée aux marchés urbains principaux de Dakar et ses environs, ainsi qu\'à la transformation agro-industrielle.',
                'type_projet_id' => TypeProjet::where('nom', 'Agriculture')->first()?->id ?? 2,
                'promoteur_id' => $promoteur->id,
                'localisation' => 'Saint-Louis, Sénégal',
                'montant_total' => 150_000_000,
                'montant_collecte' => 90_000_000,
                'montant_min_investissement' => 100_000,
                'duree_mois' => 24,
                'taux_rendement_annuel' => 15,
                'statut' => StatutProjet::FINANCEMENT,
                'date_debut' => now()->addMonth(),
                'date_fin' => now()->addMonths(3),
                'date_fermeture_collecte' => now()->addWeeks(3),
                'image_hero' => 'https://images.pexels.com/photos/4407319/pexels-photo-4407319.jpeg?auto=compress&cs=tinysrgb&w=800',
                'pourcentage_completion' => 60,
                'risques' => 'Dépendance à l\'irrigation, ravageurs, conditions climatiques imprévisibles.',
                'opportunites' => 'Marché croissant de la maraîcherie biologique, potentiel de certification bio, agritourisme.',
                'featured' => true,
                'visible' => true,
            ],
            [
                'nom' => 'Boulangerie Communautaire Dakar',
                'slug' => 'boulangerie-communautaire-dakar',
                'description_courte' => 'Production de pain et pâtisseries pour la communauté urbaine de Dakar.',
                'description' => 'Boulangerie communautaire établie à Dakar, Sénégal, spécialisée dans la production de pain traditionnel, pain français, et pâtisseries. Le projet cible la population urbaine avec des produits de qualité supérieure et des prix compétitifs. La boulangerie sera équipée d\'un four moderne et d\'équipements de production de haute capacité.',
                'type_projet_id' => TypeProjet::where('nom', 'Agroalimentaire')->first()?->id ?? 3,
                'promoteur_id' => $promoteur->id,
                'localisation' => 'Dakar, Sénégal',
                'montant_total' => 80_000_000,
                'montant_collecte' => 48_000_000,
                'montant_min_investissement' => 100_000,
                'duree_mois' => 18,
                'taux_rendement_annuel' => 22,
                'statut' => StatutProjet::FINANCEMENT,
                'date_debut' => now()->addMonth(),
                'date_fin' => now()->addMonths(3),
                'date_fermeture_collecte' => now()->addWeeks(1),
                'image_hero' => 'https://images.pexels.com/photos/2092060/pexels-photo-2092060.jpeg?auto=compress&cs=tinysrgb&w=800',
                'pourcentage_completion' => 60,
                'risques' => 'Concurrence d\'autres boulangeries, augmentation du prix de la farine, fidélité des clients.',
                'opportunites' => 'Expansion vers d\'autres quartiers, franchises, e-commerce, produits premium.',
                'featured' => false,
                'visible' => true,
            ],
            [
                'nom' => 'Pisciculture Intensive',
                'slug' => 'pisciculture-intensive',
                'description_courte' => 'Élevage de tilapia en bassins pour le marché local et régional.',
                'description' => 'Projet de pisciculture intensive situé en périphérie de Dakar. Le projet comprend 10 bassins de 1 000 m² chacun pour l\'élevage de tilapia (poisson-chat) en eau douce. La production annuelle visée est de 50 tonnes, destinée au marché local, régional et potentiellement à l\'export.',
                'type_projet_id' => TypeProjet::where('nom', 'Pisciculture')->first()?->id ?? 4,
                'promoteur_id' => $promoteur->id,
                'localisation' => 'Dakar, Sénégal',
                'montant_total' => 120_000_000,
                'montant_collecte' => 72_000_000,
                'montant_min_investissement' => 100_000,
                'duree_mois' => 24,
                'taux_rendement_annuel' => 20,
                'statut' => StatutProjet::FINANCEMENT,
                'date_debut' => now()->addMonth(),
                'date_fin' => now()->addMonths(4),
                'date_fermeture_collecte' => now()->addWeeks(2),
                'image_hero' => 'https://images.pexels.com/photos/3962286/pexels-photo-3962286.jpeg?auto=compress&cs=tinysrgb&w=800',
                'pourcentage_completion' => 60,
                'risques' => 'Maladies des poissons, qualité de l\'eau, fluctuations du marché du poisson.',
                'opportunites' => 'Marché croissant de la tilapia, intégration vers la transformation (filerie), agritourisme.',
                'featured' => false,
                'visible' => true,
            ],
            [
                'nom' => 'Transformation de Fruits Tropicaux',
                'slug' => 'transformation-fruits-tropicaux',
                'description_courte' => 'Usine de jus et confitures à partir de mangues et goyaves locales.',
                'description' => 'Usine de transformation agroalimentaire situées à Kaolack, Sénégal. Le projet transforme les fruits tropicaux locaux (mangues, goyaves, papayes) en jus, nectars, confitures et autres produits à valeur ajoutée. La production sera commercialisée localement et potentiellement exportée vers d\'autres pays d\'Afrique de l\'Ouest.',
                'type_projet_id' => TypeProjet::where('nom', 'Agroalimentaire')->first()?->id ?? 3,
                'promoteur_id' => $promoteur->id,
                'localisation' => 'Kaolack, Sénégal',
                'montant_total' => 200_000_000,
                'montant_collecte' => 100_000_000,
                'montant_min_investissement' => 100_000,
                'duree_mois' => 30,
                'taux_rendement_annuel' => 16,
                'statut' => StatutProjet::FINANCEMENT,
                'date_debut' => now()->addMonths(2),
                'date_fin' => now()->addMonths(5),
                'date_fermeture_collecte' => now()->addWeeks(4),
                'image_hero' => 'https://images.pexels.com/photos/3962286/pexels-photo-3962286.jpeg?auto=compress&cs=tinysrgb&w=800',
                'pourcentage_completion' => 50,
                'risques' => 'Disponibilité saisonnière des fruits, compétition commerciale, normes de certification.',
                'opportunites' => 'Certification bio, export régional, partenariat avec distributeurs internationaux.',
                'featured' => false,
                'visible' => true,
            ],
        ];

        foreach ($projets as $projetData) {
            $projet = Projet::create($projetData);

            // Créer des tickets d'investissement
            $this->createTickets($projet);

            // Créer la répartition du budget
            $this->createBudgetAllocation($projet);

            // Créer des projections financières
            $this->createFinancialProjections($projet);

            // Créer des indicateurs de résultats
            $this->createResultIndicators($projet);
        }

        $this->command->info('✅ Projets avec données réalistes créés avec succès !');
    }

    private function createTickets(Projet $projet): void
    {
        // Ticket 1 : Découverte
        TicketInvestissement::create([
            'projet_id' => $projet->id,
            'nom' => 'Ticket Découverte',
            'description' => 'Parfait pour débuter votre investissement. Rendement mensuel garanti.',
            'montant_min' => 100_000,
            'montant_max' => 500_000,
            'montant_disponible' => 300_000_000,
            'nombre_places_max' => 300,
            'nombre_places_disponibles' => 300,
            'taux_rendement' => 18.00,
            'type_rendement' => 'mensuel',
            'date_debut' => now()->addMonth(),
            'date_fin' => now()->addMonths(3),
            'delai_remboursement_mois' => 36,
            'conditions' => 'Investissement minimum 100 000 FCFA. Rendement mensuel versé en fin de mois.',
            'actif' => true,
        ]);

        // Ticket 2 : Initiation
        TicketInvestissement::create([
            'projet_id' => $projet->id,
            'nom' => 'Ticket Initiation',
            'description' => 'Pour les investisseurs confirmés. Rendement trimestriel optimisé.',
            'montant_min' => 500_000,
            'montant_max' => 1_000_000,
            'montant_disponible' => 200_000_000,
            'nombre_places_max' => 200,
            'nombre_places_disponibles' => 200,
            'taux_rendement' => 19.50,
            'type_rendement' => 'trimestriel',
            'date_debut' => now()->addMonth(),
            'date_fin' => now()->addMonths(3),
            'delai_remboursement_mois' => 36,
            'conditions' => 'Investissement entre 500 000 et 1 000 000 FCFA. Rendement trimestriel.',
            'actif' => true,
        ]);

        // Ticket 3 : Croissance
        TicketInvestissement::create([
            'projet_id' => $projet->id,
            'nom' => 'Ticket Croissance',
            'description' => 'Pour les investisseurs averti. Rendement annuel compétitif.',
            'montant_min' => 1_000_000,
            'montant_max' => 5_000_000,
            'montant_disponible' => 150_000_000,
            'nombre_places_max' => 150,
            'nombre_places_disponibles' => 150,
            'taux_rendement' => 20.00,
            'type_rendement' => 'annuel',
            'date_debut' => now()->addMonth(),
            'date_fin' => now()->addMonths(3),
            'delai_remboursement_mois' => 36,
            'conditions' => 'Investissement entre 1 000 000 et 5 000 000 FCFA. Rendement annuel versé en fin d\'année.',
            'actif' => true,
        ]);

        // Ticket 4 : Stratégique
        TicketInvestissement::create([
            'projet_id' => $projet->id,
            'nom' => 'Ticket Stratégique',
            'description' => 'Pour les investisseurs institutionnels. Rendement maximal à maturité.',
            'montant_min' => 5_000_000,
            'montant_max' => 10_000_000,
            'montant_disponible' => 100_000_000,
            'nombre_places_max' => 100,
            'nombre_places_disponibles' => 100,
            'taux_rendement' => 22.00,
            'type_rendement' => 'à_maturité',
            'date_debut' => now()->addMonth(),
            'date_fin' => now()->addMonths(3),
            'delai_remboursement_mois' => 36,
            'conditions' => 'Investissement minimum 5 000 000 FCFA. Rendement versé à la fin du projet (maturité).',
            'actif' => true,
        ]);
    }

    private function createBudgetAllocation(Projet $projet): void
    {
        $allocations = [
            [
                'categorie' => 'Infrastructure & équipements',
                'montant_prevu' => $projet->montant_total * 0.40,
                'description' => 'Construction, aménagement et acquisition des équipements nécessaires au projet.',
                'statut' => 'planifié',
                'pourcentage_completion' => 0,
                'notes' => 'Budget destiné aux infrastructures et équipements principaux.',
            ],
            [
                'categorie' => 'Ressources humaines',
                'montant_prevu' => $projet->montant_total * 0.25,
                'description' => 'Salaires, rémunérations et charges liées au personnel du projet.',
                'statut' => 'planifié',
                'pourcentage_completion' => 0,
                'notes' => 'Budget prévu pour les ressources humaines sur les 12 premiers mois.',
            ],
            [
                'categorie' => 'Matières premières & stocks',
                'montant_prevu' => $projet->montant_total * 0.20,
                'description' => 'Achat des matières premières, fournitures et constitution des stocks.',
                'statut' => 'planifié',
                'pourcentage_completion' => 0,
                'notes' => 'Budget destiné à assurer la disponibilité des matières premières.',
            ],
            [
                'categorie' => 'Frais d’exploitation & imprévus',
                'montant_prevu' => $projet->montant_total * 0.15,
                'description' => 'Frais de fonctionnement, charges opérationnelles et dépenses imprévues.',
                'statut' => 'planifié',
                'pourcentage_completion' => 0,
                'notes' => 'Réserve destinée aux dépenses opérationnelles et aux éventuels imprévus.',
            ],
        ];

        foreach ($allocations as $allocation) {
            RepartitionBudget::create([
                'projet_id' => $projet->id,
                'categorie' => $allocation['categorie'],
                'montant_prevu' => $allocation['montant_prevu'],
                'montant_reel' => 0,
                'description' => $allocation['description'],
                'statut' => $allocation['statut'],
                'pourcentage_completion' => $allocation['pourcentage_completion'],
                'notes' => $allocation['notes'],
            ]);
        }
    }
    private function createFinancialProjections(Projet $projet): void
    {
        $startDate = $projet->date_debut;

        $monthlyRevenue = ($projet->montant_total / 12) * 0.35;
        $monthlyCharges = ($projet->montant_total / 12) * 0.60;

        for ($i = 1; $i <= 12; $i++) {
            $revenus = $monthlyRevenue * $i;
            $charges = $monthlyCharges * $i;
            $fluxTresorerie = $revenus - $charges;
            $beneficeNet = $revenus - $charges;

            ProjectionFinanciere::create([
                'projet_id' => $projet->id,
                'mois_projection' => $i,
                'date_projection' => $startDate->copy()->addMonths($i - 1),
                'revenus_projetes' => $revenus,
                'charges_projetes' => $charges,
                'flux_tresorerie' => $fluxTresorerie,
                'benefice_net_projete' => $beneficeNet,
                'taux_croissance' => $i > 1 ? 5.00 : null,
                'assumptions' => 'Projection basée sur une croissance progressive des revenus et des charges opérationnelles.',
                'notes' => 'Projection financière mensuelle du projet.',
            ]);
        }
    }
    private function createResultIndicators(Projet $projet): void
    {
        $indicators = [
            [
                'nom' => 'Taux de réussite',
                'description' => 'Pourcentage de réussite du projet selon les objectifs définis.',
                'unite' => '%',
                'valeur_cible' => 95,
                'valeur_actuelle' => 85,
                'type_indicateur' => 'de_performance',
                'interpretation' => 'Le taux de réussite actuel est satisfaisant mais reste inférieur à la cible fixée.',
                'actions_correctives' => 'Renforcer le suivi des activités et identifier les facteurs limitant la performance.',
            ],
            [
                'nom' => 'Taux de croissance',
                'description' => 'Taux de croissance enregistré par le projet.',
                'unite' => '%',
                'valeur_cible' => 20,
                'valeur_actuelle' => 15,
                'type_indicateur' => 'financier',
                'interpretation' => 'La croissance actuelle est positive mais inférieure à l’objectif prévu.',
                'actions_correctives' => 'Améliorer les stratégies commerciales et optimiser les ressources disponibles.',
            ],
            [
                'nom' => 'Satisfaction clients',
                'description' => 'Niveau de satisfaction des clients concernant les produits ou services du projet.',
                'unite' => '%',
                'valeur_cible' => 95,
                'valeur_actuelle' => 90,
                'type_indicateur' => 'de_performance',
                'interpretation' => 'Le niveau de satisfaction est élevé et proche de la cible.',
                'actions_correctives' => 'Maintenir la qualité des services et recueillir régulièrement les retours des clients.',
            ],
        ];

        foreach ($indicators as $indicator) {
            IndicateurResultat::create([
                'projet_id' => $projet->id,
                'nom' => $indicator['nom'],
                'description' => $indicator['description'],
                'unite' => $indicator['unite'],
                'valeur_cible' => $indicator['valeur_cible'],
                'valeur_actuelle' => $indicator['valeur_actuelle'],
                'valeur_precedente' => $indicator['valeur_actuelle'] * 0.95,
                'type_indicateur' => $indicator['type_indicateur'],
                'date_mesure' => $projet->date_debut,
                'prochaine_date_mesure' => $projet->date_debut->copy()->addMonth(),
                'interpretation' => $indicator['interpretation'],
                'actions_correctives' => $indicator['actions_correctives'],
            ]);
        }
    }}
