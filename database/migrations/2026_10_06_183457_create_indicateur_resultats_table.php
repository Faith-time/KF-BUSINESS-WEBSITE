<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicateur_resultats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->string('nom');
            $table->text('description');
            $table->string('unite'); // unité de mesure (%, unités, FCFA, etc.)
            $table->decimal('valeur_cible', 12, 2);
            $table->decimal('valeur_actuelle', 12, 2)->default(0);
            $table->decimal('valeur_precedente', 12, 2)->nullable();
            $table->string('type_indicateur'); // financier, opérationnel, de_performance
            $table->date('date_mesure');
            $table->date('prochaine_date_mesure')->nullable();
            $table->text('interpretation')->nullable();
            $table->text('actions_correctives')->nullable();
            $table->timestamps();
            $table->index('projet_id');
            $table->index('date_mesure');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicateur_resultats');
    }
};
