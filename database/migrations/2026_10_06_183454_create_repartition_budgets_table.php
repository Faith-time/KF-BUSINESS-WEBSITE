<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repartition_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->string('categorie');
            $table->decimal('montant_prevu', 12, 2);
            $table->decimal('montant_reel', 12, 2)->default(0);
            $table->text('description');
            $table->string('statut'); // planifié, en_cours, terminé, dépassé
            $table->decimal('pourcentage_completion', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('projet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartition_budgets');
    }
};
