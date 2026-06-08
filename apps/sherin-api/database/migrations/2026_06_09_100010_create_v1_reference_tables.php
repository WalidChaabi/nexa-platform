<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->text('template_content');
            $table->unsignedTinyInteger('status')->default(1);
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->integer('age_min')->default(0);
            $table->integer('age_max')->default(100);
            $table->string('sexe')->default('both');
            $table->integer('points')->default(0);
            $table->integer('last_visit')->default(0);
            $table->time('heure_start')->default('00:00:00');
            $table->time('heure_end')->default('23:59:59');
            $table->timestamps();
        });

        Schema::create('product_quantites', function (Blueprint $table) {
            $table->id();
            $table->string('quantite');
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->integer('date_start');
            $table->timestamps();
        });

        Schema::create('historique_paiements', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id');
            $table->string('cmd');
            $table->double('total_ht');
            $table->double('total_ttc');
            $table->text('services');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_paiements');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('product_quantites');
        Schema::dropIfExists('filters');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('activities');
    }
};
