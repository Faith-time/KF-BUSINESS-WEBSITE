<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_investisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('type_investisseur'); // individu, entreprise, institutionnel
            $table->decimal('montant_min_investissement', 12, 2)->nullable();
            $table->decimal('montant_max_investissement', 12, 2)->nullable();
            $table->string('secteur_interet')->nullable();
            $table->string('risque_preference'); // conservateur, modéré, agressif
            $table->text('experience_investissement')->nullable();
            $table->string('localisation')->nullable();
            $table->string('devise_preference')->default('FCFA');
            $table->boolean('notifications_email')->default(true);
            $table->boolean('notifications_sms')->default(false);
            $table->text('preferences_additionnelles')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_investisseurs');
    }
};
