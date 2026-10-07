<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('methode_paiements', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->text('description')->nullable();
            $table->string('code')->unique(); // wave, mtn, orange, bank_transfer
            $table->boolean('active')->default(true);
            $table->json('config')->nullable(); // Pour stocker les clés d'API, etc.
            $table->decimal('frais_pourcentage', 5, 2)->default(0);
            $table->decimal('frais_fixes', 10, 2)->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('methode_paiements');
    }
};
