<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_investissements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->string('nom');
            $table->text('description');
            $table->decimal('montant_min', 12, 2);
            $table->decimal('montant_max', 12, 2);
            $table->decimal('montant_disponible', 12, 2);
            $table->integer('nombre_places_max');
            $table->integer('nombre_places_disponibles');
            $table->decimal('taux_rendement', 5, 2);
            $table->string('type_rendement'); // mensuel, trimestriel, annuel, à_maturité
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('delai_remboursement_mois');
            $table->text('conditions')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->index('projet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_investissements');
    }
};
