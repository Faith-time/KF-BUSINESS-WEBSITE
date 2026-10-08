<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Comptable;
use App\Http\Controllers\Investisseur;
use App\Http\Controllers\Public as Visiteur;
use Illuminate\Support\Facades\Route;

/* ========== SITE PUBLIC - Aucune authentification requise ========== */

// Page d'accueil
Route::get('/', [Visiteur\PageController::class, 'home'])->name('accueil');

// Pages statiques
Route::get('/a-propos', [Visiteur\PageController::class, 'apropos'])->name('apropos');
Route::get('/mentions-legales', [Visiteur\PageController::class, 'mentions'])->name('mentions');
Route::get('/politique-de-confidentialite', [Visiteur\PageController::class, 'confidentialite'])->name('confidentialite');
Route::get('/conditions-generales', [Visiteur\PageController::class, 'cgv'])->name('cgv');

// Pages Inertia statiques
Route::inertia('/comment-ca-marche', 'Public/CommentInvestir')->name('comment-investir');
Route::inertia('/transparence', 'Public/Transparence')->name('transparence');
Route::inertia('/contact', 'Public/Contact')->name('contact');

// Projets
Route::prefix('projets')->name('projets.')->group(function () {
    Route::get('/', [Visiteur\ProjetController::class, 'index'])->name('index');
    Route::get('/recherche', [Visiteur\ProjetController::class, 'search'])->name('recherche');
    Route::get('/{projet:slug}', [Visiteur\ProjetController::class, 'show'])->name('show');
});

/* ========== REDIRECTION POST-AUTHENTIFICATION ========== */
/*
 * Route qui redirige les utilisateurs connectés vers leur dashboard respectif
 * selon leur rôle (admin, comptable, investisseur)
 */
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = request()->user();

    return match (true) {
        $user->hasRole('administrateur') => to_route('admin.dashboard'),
        $user->hasRole('comptable') => to_route('comptable.dashboard'),
        default => to_route('investisseur.dashboard'),
    };
})->name('dashboard');

/* ========== ESPACE INVESTISSEUR ========== */
Route::middleware(['auth', 'verified', 'role:investisseur'])
    ->prefix('investisseur')
    ->name('investisseur.')
    ->group(function () {
        // Dashboard
        Route::get('/', Investisseur\TableauDeBordController::class)
            ->name('dashboard');

        // Projets
        Route::get('projets', [Investisseur\ProjetController::class, 'index'])
            ->name('projets.index');
        Route::get('projets/{projet}', [Investisseur\ProjetController::class, 'show'])
            ->name('projets.show');

        // Souscriptions
        Route::resource('souscriptions', Investisseur\SouscriptionController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::patch('souscriptions/{souscription}/annuler', [Investisseur\SouscriptionController::class, 'cancel'])
            ->name('souscriptions.cancel');

        // Paiements
        Route::get('souscriptions/{souscription}/paiement', [Investisseur\PaiementController::class, 'create'])
            ->name('paiements.create');
        Route::post('souscriptions/{souscription}/paiement', [Investisseur\PaiementController::class, 'store'])
            ->name('paiements.store');
        Route::get('paiements', [Investisseur\PaiementController::class, 'index'])
            ->name('paiements.index');
        Route::get('paiements/{paiement}', [Investisseur\PaiementController::class, 'show'])
            ->name('paiements.show');

        // Portefeuille & Résultats
        Route::get('portefeuille', Investisseur\PortefeuilleController::class)
            ->name('portefeuille');
        Route::get('resultats', Investisseur\ResultatController::class)
            ->name('resultats');

        // Documents
        Route::get('documents', [Investisseur\DocumentController::class, 'index'])
            ->name('documents.index');
        Route::get('documents/{document}', [Investisseur\DocumentController::class, 'show'])
            ->name('documents.show');
        Route::post('documents/{document}/telecharger', [Investisseur\DocumentController::class, 'download'])
            ->name('documents.download');

        // Profil
        Route::get('profil', [Investisseur\ProfilController::class, 'edit'])
            ->name('profil.edit');
        Route::put('profil', [Investisseur\ProfilController::class, 'update'])
            ->name('profil.update');

        // KYC (Connaissance de la Clientèle)
        Route::get('kyc', [Investisseur\DossierKycController::class, 'show'])
            ->name('kyc.show');
        Route::get('kyc/ajouter', [Investisseur\DossierKycController::class, 'create'])
            ->name('kyc.create');
        Route::post('kyc', [Investisseur\DossierKycController::class, 'store'])
            ->name('kyc.store');
    });

/* ========== ESPACE COMPTABLE ========== */
Route::middleware(['auth', 'verified', 'role:comptable'])
    ->prefix('comptable')
    ->name('comptable.')
    ->group(function () {
        // Dashboard
        Route::get('/', Comptable\TableauDeBordController::class)
            ->name('dashboard');

        // Paiements
        Route::get('paiements', [Comptable\PaiementController::class, 'index'])
            ->name('paiements.index');
        Route::get('paiements/{paiement}', [Comptable\PaiementController::class, 'show'])
            ->name('paiements.show');
        Route::patch('paiements/{paiement}/confirmer', [Comptable\PaiementController::class, 'confirmer'])
            ->name('paiements.confirmer');
        Route::patch('paiements/{paiement}/rejeter', [Comptable\PaiementController::class, 'rejeter'])
            ->name('paiements.rejeter');

        // Souscriptions
        Route::get('souscriptions', [Comptable\SouscriptionController::class, 'index'])
            ->name('souscriptions.index');
        Route::get('souscriptions/{souscription}', [Comptable\SouscriptionController::class, 'show'])
            ->name('souscriptions.show');

        // Investisseurs
        Route::get('investisseurs', [Comptable\InvestisseurController::class, 'index'])
            ->name('investisseurs.index');
        Route::get('investisseurs/{user}', [Comptable\InvestisseurController::class, 'show'])
            ->name('investisseurs.show');

        // Documents
        Route::get('documents', [Comptable\DocumentController::class, 'index'])
            ->name('documents.index');
        Route::get('documents/{document}', [Comptable\DocumentController::class, 'show'])
            ->name('documents.show');
        Route::post('documents/{document}/telecharger', [Comptable\DocumentController::class, 'download'])
            ->name('documents.download');

        // Exports
        Route::get('exports/{type}', [Comptable\ExportController::class, 'telecharger'])
            ->name('exports.telecharger');
    });

/* ========== ESPACE ADMINISTRATEUR ========== */
Route::middleware(['auth', 'verified', 'role:administrateur'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', Admin\TableauDeBordController::class)
            ->name('dashboard');

        // ===== GESTION DES PROJETS =====
        Route::resource('projets', Admin\ProjetController::class);

        // Ressources imbriquées des projets
        Route::resource('projets.medias', Admin\MediaProjetController::class)
            ->except(['index', 'show'])
            ->parameters(['medias' => 'media_projet']);

        Route::resource('projets.tickets', Admin\TicketInvestissementController::class)
            ->except(['index', 'show'])
            ->parameters(['tickets' => 'ticket_investissement']);

        Route::resource('projets.budget', Admin\RepartitionBudgetController::class)
            ->except(['index', 'show'])
            ->parameters(['budget' => 'repartition_budget']);

        Route::resource('projets.projections', Admin\ProjectionFinanciereController::class)
            ->except(['index', 'show'])
            ->parameters(['projections' => 'projection_financiere']);

        Route::resource('projets.indicateurs', Admin\IndicateurResultatController::class)
            ->except(['index', 'show'])
            ->parameters(['indicateurs' => 'indicateur_resultat']);

        // Types de projets
        Route::resource('types-projets', Admin\TypeProjetController::class)
            ->except(['show'])
            ->parameters(['types-projets' => 'type_projet']);

        // ===== GESTION DES INVESTISSEURS =====
        Route::get('investisseurs', [Admin\InvestisseurController::class, 'index'])
            ->name('investisseurs.index');
        Route::get('investisseurs/{user}', [Admin\InvestisseurController::class, 'show'])
            ->name('investisseurs.show');
        Route::get('investisseurs/{user}/edit', [Admin\InvestisseurController::class, 'edit'])
            ->name('investisseurs.edit');
        Route::put('investisseurs/{user}', [Admin\InvestisseurController::class, 'update'])
            ->name('investisseurs.update');

        // ===== KYC (VÉRIFICATION D'IDENTITÉ) =====
        Route::get('kyc', [Admin\DossierKycController::class, 'index'])
            ->name('kyc.index');
        Route::get('kyc/{dossierKyc}', [Admin\DossierKycController::class, 'show'])
            ->name('kyc.show');
        Route::patch('kyc/{dossierKyc}/approuver', [Admin\DossierKycController::class, 'approuver'])
            ->name('kyc.approuver');
        Route::patch('kyc/{dossierKyc}/rejeter', [Admin\DossierKycController::class, 'rejeter'])
            ->name('kyc.rejeter');
        Route::patch('kyc/{dossierKyc}/demander-revision', [Admin\DossierKycController::class, 'requestRevision'])
            ->name('kyc.request-revision');

        // ===== SOUSCRIPTIONS =====
        Route::get('souscriptions', [Admin\SouscriptionController::class, 'index'])
            ->name('souscriptions.index');
        Route::get('souscriptions/{souscription}', [Admin\SouscriptionController::class, 'show'])
            ->name('souscriptions.show');
        Route::patch('souscriptions/{souscription}/approuver', [Admin\SouscriptionController::class, 'approuver'])
            ->name('souscriptions.approuver');
        Route::patch('souscriptions/{souscription}/rejeter', [Admin\SouscriptionController::class, 'rejeter'])
            ->name('souscriptions.rejeter');

        // ===== PAIEMENTS =====
        Route::get('paiements', [Admin\PaiementController::class, 'index'])
            ->name('paiements.index');
        Route::get('paiements/{paiement}', [Admin\PaiementController::class, 'show'])
            ->name('paiements.show');
        Route::patch('paiements/{paiement}/confirmer', [Admin\PaiementController::class, 'confirmer'])
            ->name('paiements.confirmer');
        Route::patch('paiements/{paiement}/rejeter', [Admin\PaiementController::class, 'rejeter'])
            ->name('paiements.rejeter');
        Route::patch('paiements/{paiement}/rembourser', [Admin\PaiementController::class, 'rembourser'])
            ->name('paiements.rembourser');

        // Méthodes de paiement
        Route::resource('methodes-paiement', Admin\MethodePaiementController::class)
            ->except(['show'])
            ->parameters(['methodes-paiement' => 'methode_paiement']);
        Route::patch('methodes-paiement/{methode_paiement}/toggle', [Admin\MethodePaiementController::class, 'toggle'])
            ->name('methodes-paiement.toggle');

        // ===== DIVIDENDES =====
        Route::get('dividendes', [Admin\DividendeController::class, 'index'])
            ->name('dividendes.index');
        Route::get('dividendes/creer', [Admin\DividendeController::class, 'create'])
            ->name('dividendes.create');
        Route::post('dividendes', [Admin\DividendeController::class, 'store'])
            ->name('dividendes.store');
        Route::get('dividendes/{dividende}', [Admin\DividendeController::class, 'show'])
            ->name('dividendes.show');
        Route::patch('dividendes/{dividende}/approuver', [Admin\DividendeController::class, 'approuver'])
            ->name('dividendes.approuver');
        Route::patch('dividendes/{dividende}/verser', [Admin\DividendeController::class, 'verser'])
            ->name('dividendes.verser');

        // ===== DOCUMENTS =====
        Route::resource('documents', Admin\DocumentController::class)
            ->except(['edit', 'update']);
        Route::patch('documents/{document}/publier', [Admin\DocumentController::class, 'publish'])
            ->name('documents.publish');
        Route::patch('documents/{document}/depublier', [Admin\DocumentController::class, 'unpublish'])
            ->name('documents.unpublish');

        // ===== CONTENU & PAGES =====
        Route::resource('pages-contenu', Admin\PageContenuController::class)
            ->only(['index', 'edit', 'update'])
            ->parameters(['pages-contenu' => 'page_contenu']);
        Route::patch('pages-contenu/{page_contenu}/publier', [Admin\PageContenuController::class, 'publish'])
            ->name('pages-contenu.publish');
        Route::patch('pages-contenu/{page_contenu}/depublier', [Admin\PageContenuController::class, 'unpublish'])
            ->name('pages-contenu.unpublish');

        // ===== AUDIT & LOGS =====
        Route::get('journal', Admin\JournalController::class)
            ->name('journal');

        // ===== ADMINISTRATION UTILISATEURS =====
        Route::get('utilisateurs', [Admin\UtilisateurController::class, 'index'])
            ->name('utilisateurs.index');
        Route::get('utilisateurs/{user}/edit', [Admin\UtilisateurController::class, 'edit'])
            ->name('utilisateurs.edit');
        Route::put('utilisateurs/{user}', [Admin\UtilisateurController::class, 'update'])
            ->name('utilisateurs.update');
        Route::patch('utilisateurs/{user}/changer-mot-de-passe', [Admin\UtilisateurController::class, 'changePassword'])
            ->name('utilisateurs.change-password');

        // ===== EXPORTS =====
        Route::get('exports/{type}', [Admin\ExportController::class, 'telecharger'])
            ->name('exports.telecharger');
    });

// ========== Paramètres & configuration Jetstream ==========
require __DIR__.'/settings.php';
