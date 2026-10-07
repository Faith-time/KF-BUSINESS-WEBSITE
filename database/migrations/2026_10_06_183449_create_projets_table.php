<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutProjet;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();            $table->text('description');
            $table->text('description_courte');
            $table->foreignId('type_projet_id')->constrained('type_projets')->onDelete('cascade');
            $table->foreignId('promoteur_id')->constrained('users')->onDelete('cascade');
            $table->string('localisation');
            $table->decimal('montant_total', 15, 2);
            $table->decimal('montant_collecte', 15, 2)->default(0);
            $table->decimal('montant_min_investissement', 12, 2);
            $table->integer('duree_mois');
            $table->decimal('taux_rendement_annuel', 5, 2);
            $table->string('statut')->default(StatutProjet::PREPARATION->value);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_fermeture_collecte');
            $table->string('image_hero')->nullable();
            $table->integer('nombre_investisseurs')->default(0);
            $table->decimal('pourcentage_completion', 5, 2)->default(0);
            $table->text('risques')->nullable();
            $table->text('opportunites')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('visible')->default(true);
            $table->timestamps();
            $table->index('statut');
            $table->index('date_debut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
