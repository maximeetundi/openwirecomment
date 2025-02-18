<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('owc_comments', function (Blueprint $table) {
            $table->id();
            // Relation polymorphique
            $table->morphs('commentable');
            // Lien vers l'utilisateur (optionnel)
            $table->unsignedBigInteger('user_id')->nullable();
            // Informations pour les visiteurs non authentifiés
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            // Contenu du commentaire
            $table->text('content');
            // Chemin du fichier attaché (optionnel)
            $table->string('attachment')->nullable();
            // Pour les réponses (commentaire enfant)
            $table->unsignedBigInteger('parent_id')->nullable();
            // Enregistrement des informations de l'utilisateur
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
