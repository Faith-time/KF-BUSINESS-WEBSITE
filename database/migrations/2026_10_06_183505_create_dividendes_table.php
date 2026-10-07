<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividendes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscription_id')->constrained('souscriptions')->onDelete('cascade');
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->string('numero_dividende')->unique();
            $table->integer('numero_periode'); // 1, 2, 3, etc.
            $table->date('date_periode_debut');
            $table->date('date_periode_fin');
            $table->decimal('montant_brut', 12, 2);
            $table->decimal('taux_dividende', 5, 2);
            $table->decimal('retenue_source', 10, 2)->default(0);
            $table->decimal('montant_net', 12, 2);
            $table->string('statut'); // planifié, approuvé, versé, reporté
            $table->date('date_paiement_prevu');
            $table->date('date_paiement_reel')->nullable();
            $table->string('methode_versement')->nullable(); // virement, Wave, MTN, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('souscription_id');
            $table->index('projet_id');
            $table->index('date_periode_debut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividendes');
    }
};
