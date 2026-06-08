<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('age_min')->default(0);
            $table->integer('age_max')->default(100);
            $table->string('sexe')->default('both');
            $table->integer('points')->default(0); // points de reduction;
            $table->integer('last_visit')->default(0);// la date de dernier visite 
            $table->time('heure_start')->default('00:00:00');
            $table->time('heure_end')->default('23:59:59');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
}
