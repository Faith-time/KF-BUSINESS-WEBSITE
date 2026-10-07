<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutKyc;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossier_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('statut')->default(StatutKyc::NOT_STARTED->value);
            $table->string('numero_identification')->nullable();
            $table->string('type_identification'); // CNI, Passport, RCCM, etc.
            $table->date('date_delivrance')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('lieu_delivrance')->nullable();
            $table->string('adresse_complete')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->nullable();
            $table->string('telephone_verification')->nullable();
            $table->boolean('telephone_verifie')->default(false);
            $table->string('numero_compte_bancaire')->nullable();
            $table->string('nom_banque')->nullable();
            $table->string('code_swift')->nullable();
            $table->string('adresse_banque')->nullable();
            $table->text('sources_fonds')->nullable();
            $table->text('activite_professionnelle')->nullable();
            $table->string('secteur_activite')->nullable();
            $table->text('experience_investissement')->nullable();
            $table->string('nom_personne_reference')->nullable();
            $table->string('telephone_reference')->nullable();
            $table->string('email_reference')->nullable();
            $table->text('documents_supports')->nullable(); // JSON array of file paths
            $table->text('raison_rejet')->nullable();
            $table->timestamp('date_rejet')->nullable();
            $table->timestamp('date_approbation')->nullable();
            $table->foreignId('verify_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes_verification')->nullable();
            $table->timestamps();
            $table->index('statut');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_kycs');
    }
};
