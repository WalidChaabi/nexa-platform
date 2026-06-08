<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('user_id');
            $table->integer('employ_id');
            $table->integer('customer_id');
            $table->integer('for_sexe');
            $table->tinyInteger('description');
            $table->dateTime('invoice_date');
            $table->float('total')->default(0);
            $table->float('total_ht')->nullable()->default(0);
            $table->float('tva')->nullable()->default(0);
            $table->float('paid_amount');
            $table->tinyInteger('payment_method')->default(0)->comment="0:Espece;1:check;2:credit card";
            $table->integer('points');
            $table->tinyInteger('status')->defualt(0)->comment="0:pending;4:cancelled";
            $table->float('reste')->nullable()->default(0);
            $table->float('reste_payer')->nullable()->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
