<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutPaiement;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_paiement')->unique();
            $table->foreignId('souscription_id')->constrained('souscriptions')->onDelete('cascade');
            $table->foreignId('investisseur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('methode_paiement_id')->constrained('methode_paiements')->onDelete('cascade');
            $table->decimal('montant', 12, 2);
            $table->decimal('frais', 10, 2)->default(0);
            $table->decimal('montant_total', 12, 2);
            $table->string('statut')->default(StatutPaiement::PENDING->value);
            $table->date('date_paiement');
            $table->date('date_confirmation')->nullable();
            $table->string('reference_externe')->nullable(); // ID de la transaction externe (Wave, MTN, etc.)
            $table->text('raison_echec')->nullable();
            $table->timestamp('date_echec')->nullable();
            $table->timestamp('date_remboursement')->nullable();
            $table->string('montant_devise_origine')->nullable();
            $table->string('code_devise_origine')->nullable(); // XOF, EUR, USD, etc.
            $table->decimal('taux_change', 10, 4)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('souscription_id');
            $table->index('investisseur_id');
            $table->index('statut');
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
