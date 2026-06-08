<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

class CreateCampaignHistoriquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_historiques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('camp_id');
            $table->integer('invoice_id');
            $table->integer('store_id');
            $table->integer('customer_id');
            $table->string('phone');
            $table->string('code_red');
            $table->datetime('start_date');
            $table->integer('status');
            $table->integer('expired_code');
            $table->integer('is_payed');
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
        Schema::dropIfExists('campaign_historiques');
    }
}
