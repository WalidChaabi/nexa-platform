<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

class CreateMessageCampaignsTable extends Migration
{
    public function up()
    {
        Schema::create('message_campaigns', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            // $table->integer('id');
            $table->increments('id');

            $table->unsignedBigInteger('message_id');
            $table->foreign('message_id')
            ->references('id')->on('messages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->unsignedBigInteger('campaign_detail_id');
            $table->foreign('campaign_detail_id')
            ->references('id')->on('campaign_details')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_campaigns');
    }
}
