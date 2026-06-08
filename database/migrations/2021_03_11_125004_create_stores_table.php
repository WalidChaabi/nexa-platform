<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('activity_id');
            $table->string('logo')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('contact');
            $table->text('address');
            $table->integer('base');
            $table->integer('base_profit');
            $table->integer('coeff');
            $table->integer('tva')->default(0);
            $table->tinyInteger('status')->default(1)->comment="0:innactie,1:active";
            $table->timestamps();
            $table->text('invoice_message')->default(NULL);
            $table->integer('allow_camp')->default(0);
            $table->string('sender_id');
            $table->integer('reste')->default(0);
            $table->integer('rendez_vous')->default(0);
            $table->integer('praticien')->default(0);
            $table->integer('Anniv')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
