<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Comptable;
use App\Http\Controllers\Investisseur;
use App\Http\Controllers\Public as Visiteur;
use Illuminate\Support\Facades\Route;

/* ---------- Site public (visiteur) ---------- */
Route::get('/', [Visiteur\PageController::class, 'home'])->name('accueil');
Route::get('/a-propos', [Visiteur\PageController::class, 'apropos'])->name('apropos');
Route::get('/projets', [Visiteur\ProjetController::class, 'index'])->name('projets.index');
Route::get('/projets/{projet}', [Visiteur\ProjetController::class, 'show'])->name('projets.show');

/* ---------- Redirection après connexion selon le rôle ---------- */
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = request()->user();

    return match (true) {
        $user->hasRole('administrateur') => to_route('admin.dashboard'),
        $user->hasRole('comptable') => to_route('comptable.dashboard'),
        default => to_route('investisseur.dashboard'),
    };
})->name('dashboard');

/* ---------- Espace investisseur ---------- */
Route::middleware(['auth', 'verified', 'role:investisseur'])
    ->prefix('investisseur')->name('investisseur.')->group(function () {
        Route::get('/', Investisseur\TableauDeBordController::class)->name('dashboard');

        Route::get('projets', [Investisseur\ProjetController::class, 'index'])->name('projets.index');
        Route::get('projets/{projet}', [Investisseur\ProjetController::class, 'show'])->name('projets.show');

        Route::resource('souscriptions', Investisseur\SouscriptionController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::get('souscriptions/{souscription}/paiement', [Investisseur\PaiementController::class, 'create'])
            ->name('paiements.create');
        Route::post('souscriptions/{souscription}/paiement', [Investisseur\PaiementController::class, 'store'])
            ->name('paiements.store');

        Route::get('portefeuille', Investisseur\PortefeuilleController::class)->name('portefeuille');
        Route::get('resultats', Investisseur\ResultatController::class)->name('resultats');

        Route::get('documents', [Investisseur\DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/{document}', [Investisseur\DocumentController::class, 'show'])->name('documents.show');

        Route::get('profil', [Investisseur\ProfilController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [Investisseur\ProfilController::class, 'update'])->name('profil.update');

        Route::get('kyc', [Investisseur\DossierKycController::class, 'create'])->name('kyc.create');
        Route::post('kyc', [Investisseur\DossierKycController::class, 'store'])->name('kyc.store');
        Route::get('kyc/statut', [Investisseur\DossierKycController::class, 'show'])->name('kyc.show');
    });

/* ---------- Espace comptable ---------- */
Route::middleware(['auth', 'verified', 'role:comptable', 'deux-facteurs'])
    ->prefix('comptable')->name('comptable.')->group(function () {
        Route::get('/', Comptable\TableauDeBordController::class)->name('dashboard');

        Route::get('paiements', [Comptable\PaiementController::class, 'index'])->name('paiements.index');
        Route::get('paiements/{paiement}', [Comptable\PaiementController::class, 'show'])->name('paiements.show');
        Route::patch('paiements/{paiement}/valider', [Comptable\PaiementController::class, 'valider'])->name('paiements.valider');
        Route::patch('paiements/{paiement}/rejeter', [Comptable\PaiementController::class, 'rejeter'])->name('paiements.rejeter');

        Route::get('souscriptions', [Comptable\SouscriptionController::class, 'index'])->name('souscriptions.index');
        Route::get('souscriptions/{souscription}', [Comptable\SouscriptionController::class, 'show'])->name('souscriptions.show');

        Route::get('investisseurs', [Comptable\InvestisseurController::class, 'index'])->name('investisseurs.index');
        Route::get('investisseurs/{user}', [Comptable\InvestisseurController::class, 'show'])->name('investisseurs.show');

        Route::get('documents', [Comptable\DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/{document}', [Comptable\DocumentController::class, 'show'])->name('documents.show');

        Route::get('exports/{type}', [Comptable\ExportController::class, 'telecharger'])->name('exports.telecharger');
    });

/* ---------- Espace administrateur ---------- */
Route::middleware(['auth', 'verified', 'role:administrateur', 'deux-facteurs'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', Admin\TableauDeBordController::class)->name('dashboard');

        // Projets et leurs éléments (gérés depuis la fiche du projet)
        Route::resource('projets', Admin\ProjetController::class);
        Route::resource('projets.medias', Admin\MediaProjetController::class)
            ->except(['index', 'show'])->parameters(['medias' => 'media_projet']);
        Route::resource('projets.tickets', Admin\TicketInvestissementController::class)
            ->except(['index', 'show'])->parameters(['tickets' => 'ticket_investissement']);
        Route::resource('projets.budget', Admin\RepartitionBudgetController::class)
            ->except(['index', 'show'])->parameters(['budget' => 'repartition_budget']);
        Route::resource('projets.projections', Admin\ProjectionFinanciereController::class)
            ->except(['index', 'show'])->parameters(['projections' => 'projection_financiere']);
        Route::resource('projets.indicateurs', Admin\IndicateurResultatController::class)
            ->except(['index', 'show'])->parameters(['indicateurs' => 'indicateur_resultat']);

        Route::resource('types-projets', Admin\TypeProjetController::class)
            ->except(['show'])->parameters(['types-projets' => 'type_projet']);

        // Investisseurs et vérification d'identité
        Route::get('investisseurs', [Admin\InvestisseurController::class, 'index'])->name('investisseurs.index');
        Route::get('investisseurs/{user}', [Admin\InvestisseurController::class, 'show'])->name('investisseurs.show');
        Route::get('kyc', [Admin\DossierKycController::class, 'index'])->name('kyc.index');
        Route::get('kyc/{dossierKyc}', [Admin\DossierKycController::class, 'show'])->name('kyc.show');
        Route::patch('kyc/{dossierKyc}/verifier', [Admin\DossierKycController::class, 'verifier'])->name('kyc.verifier');
        Route::patch('kyc/{dossierKyc}/rejeter', [Admin\DossierKycController::class, 'rejeter'])->name('kyc.rejeter');

        // Souscriptions et paiements
        Route::get('souscriptions', [Admin\SouscriptionController::class, 'index'])->name('souscriptions.index');
        Route::get('souscriptions/{souscription}', [Admin\SouscriptionController::class, 'show'])->name('souscriptions.show');
        Route::get('paiements', [Admin\PaiementController::class, 'index'])->name('paiements.index');
        Route::get('paiements/{paiement}', [Admin\PaiementController::class, 'show'])->name('paiements.show');
        Route::resource('methodes-paiement', Admin\MethodePaiementController::class)
            ->except(['show'])->parameters(['methodes-paiement' => 'methode_paiement']);

        // Contenu, documents, dividendes
        Route::resource('dividendes', Admin\DividendeController::class)->except(['show', 'edit', 'update']);
        Route::resource('documents', Admin\DocumentController::class)->except(['edit', 'update']);
        Route::resource('pages-contenu', Admin\PageContenuController::class)
            ->only(['index', 'edit', 'update'])->parameters(['pages-contenu' => 'page_contenu']);

        // Sécurité et administration
        Route::get('journal', Admin\JournalController::class)->name('journal');
        Route::get('utilisateurs', [Admin\UtilisateurController::class, 'index'])->name('utilisateurs.index');
        Route::get('utilisateurs/{user}/edit', [Admin\UtilisateurController::class, 'edit'])->name('utilisateurs.edit');
        Route::put('utilisateurs/{user}', [Admin\UtilisateurController::class, 'update'])->name('utilisateurs.update');
        Route::get('exports/{type}', [Admin\ExportController::class, 'telecharger'])->name('exports.telecharger');
    });

require __DIR__.'/settings.php';

/* ---------- Site public : pages complémentaires ---------- */
Route::get('/recherche', [Visiteur\ProjetController::class, 'search'])->name('projets.recherche');
Route::get('/mentions-legales', [Visiteur\PageController::class, 'mentions'])->name('mentions');
Route::get('/politique-de-confidentialite', [Visiteur\PageController::class, 'confidentialite'])->name('confidentialite');
Route::get('/conditions-generales', [Visiteur\PageController::class, 'cgv'])->name('cgv');
Route::inertia('/comment-ca-marche', 'Public/CommentInvestir')->name('comment-investir');
Route::inertia('/transparence', 'Public/Transparence')->name('transparence');
Route::inertia('/contact', 'Public/Contact')->name('contact');
