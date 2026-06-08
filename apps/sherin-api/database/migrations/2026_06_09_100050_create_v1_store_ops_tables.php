<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('epingles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color');
        });

        Schema::create('rdvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('event_id');
            $table->string('title');
            $table->dateTime('start');
            $table->integer('status');

            $table->index(['store_id', 'customer_id']);
            $table->index('start');
        });

        Schema::create('product_hestorique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('produit_id');
            $table->foreign('produit_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('notification');
            $table->timestamps();
        });

        Schema::create('product_quantite_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('produit_id');
            $table->foreign('produit_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreignId('product_quantite_id')->constrained('product_quantites')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->integer('number');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('table_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_products');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('product_quantite_details');
        Schema::dropIfExists('product_hestorique');
        Schema::dropIfExists('rdvs');
        Schema::dropIfExists('epingles');
    }
};
