<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductHestoriqueTable extends Migration
{

    public function up()
    {
        Schema::create('product_hestorique', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedBigInteger('store_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('produit_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('notification');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_hestorique');
    }
}
