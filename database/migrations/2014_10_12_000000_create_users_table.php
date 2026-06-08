<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->nullable();
            $table->tinyInteger('is_admin');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone',20)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('allow_share')->default(0)->comment="0:NO;1:YES";
            $table->tinyInteger('status')->default(1)->comment="0:innactif;1:actif";
            $table->tinyInteger('is_active');
            $table->timestamp('last_activity')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
