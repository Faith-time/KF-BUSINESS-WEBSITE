<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutSouscription;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('souscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('numero_souscription')->unique();
            $table->foreignId('investisseur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->foreignId('ticket_investissement_id')->constrained('ticket_investissements')->onDelete('cascade');
            $table->decimal('montant_souscrit', 12, 2);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->decimal('montant_restant', 12, 2);
            $table->integer('nombre_parts');
            $table->decimal('prix_par_part', 10, 2);
            $table->string('statut')->default(StatutSouscription::PENDING->value);
            $table->date('date_souscription');
            $table->date('date_approbation')->nullable();
            $table->date('date_paiement_complet')->nullable();
            $table->text('raison_rejet')->nullable();
            $table->timestamp('date_rejet')->nullable();
            $table->boolean('contrat_signe')->default(false);
            $table->timestamp('date_signature')->nullable();
            $table->string('chemin_contrat')->nullable();
            $table->text('conditions_additionnelles')->nullable();
            $table->decimal('frais_traitement', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('investisseur_id');
            $table->index('projet_id');
            $table->index('statut');
            $table->index('date_souscription');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('souscriptions');
    }
};
