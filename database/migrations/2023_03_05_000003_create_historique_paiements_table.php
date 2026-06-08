<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

class CreateHistoriquePaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historique_paiements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id');
            $table->string('cmd');
            $table->double('total_ht');
            $table->double('total_ttc');
            $table->text('services');
            $table->integer('status');
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
        Schema::dropIfExists('historique_paiements');
    }
}
