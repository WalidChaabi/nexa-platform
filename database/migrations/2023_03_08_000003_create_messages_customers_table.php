<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

class CreateMessagesCustomersTable extends Migration
{

    public function up()
    {
        Schema::create('messages_customers', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedBigInteger('customer_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('store_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text('message');
            $table->string('type');
            $table->integer('payed')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages_customers');
    }
}
