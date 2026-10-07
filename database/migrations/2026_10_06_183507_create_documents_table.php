<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TypeDocument;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('type')->default(TypeDocument::PRESENTATION->value);
            $table->text('description')->nullable();
            $table->foreignId('projet_id')->nullable()->constrained('projets')->onDelete('cascade');
            $table->foreignId('createur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('investisseur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('chemin_fichier');
            $table->string('nom_fichier_original');
            $table->string('extension');
            $table->integer('taille_bytes');
            $table->string('mime_type');
            $table->boolean('visible_investisseurs')->default(false);
            $table->boolean('visible_public')->default(false);
            $table->boolean('visible_comptables')->default(false);
            $table->date('date_publication')->nullable();
            $table->date('date_expiration')->nullable();
            $table->integer('nombre_telechargements')->default(0);
            $table->text('tags')->nullable(); // JSON array
            $table->timestamp('date_dernier_acces')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('projet_id');
            $table->index('createur_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
