<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('activity_id')->default(0);
            $table->string('name');
            $table->string('phone')->default('');
            $table->string('contact')->default('');
            $table->text('address')->nullable();
            $table->unsignedInteger('base')->default(0);
            $table->unsignedInteger('base_profit')->default(0);
            $table->unsignedInteger('coeff')->default(0);
            $table->unsignedTinyInteger('tva')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->text('invoice_message')->nullable();
            $table->unsignedTinyInteger('allow_camp')->default(0);
            $table->string('sender_id')->default('');
            $table->integer('reste')->default(0);
            $table->unsignedTinyInteger('rendez_vous')->default(0);
            $table->unsignedTinyInteger('praticien')->default(0);
            $table->unsignedTinyInteger('Anniv')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
