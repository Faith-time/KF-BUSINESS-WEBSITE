<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projection_financieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->integer('mois_projection'); // 1, 2, 3, etc.
            $table->date('date_projection');
            $table->decimal('revenus_projetes', 12, 2);
            $table->decimal('charges_projetes', 12, 2);
            $table->decimal('flux_tresorerie', 12, 2);
            $table->decimal('benefice_net_projete', 12, 2);
            $table->decimal('taux_croissance', 5, 2)->nullable();
            $table->text('assumptions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('projet_id');
            $table->index('date_projection');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projection_financieres');
    }
};
