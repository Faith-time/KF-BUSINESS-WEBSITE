<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TypeMedia;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_projets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->string('type')->default(TypeMedia::IMAGE->value);
            $table->string('chemin_fichier');
            $table->string('nom_fichier_original');
            $table->string('mime_type');
            $table->integer('taille_bytes');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('principal')->default(false);
            $table->timestamps();
            $table->index('projet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_projets');
    }
};
