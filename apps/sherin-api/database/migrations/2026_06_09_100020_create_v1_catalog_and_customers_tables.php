<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('daily')->default(0);
            $table->bigInteger('weekly')->default(0);
            $table->bigInteger('monthly')->default(0);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->timestamps();

            $table->index('store_id');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->decimal('price');
            $table->decimal('promotion_price');
            $table->integer('quantite')->default(0);
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('image')->nullable();

            $table->index('store_id');
            $table->index('categorie_id');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->unsignedTinyInteger('sexe')->default(0);
            $table->date('birth')->nullable();
            $table->string('points')->nullable();
            $table->timestamps();

            $table->index('store_id');
            $table->index('phone');
        });

        Schema::create('customers_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->unique(['store_id', 'customer_id']);
        });

        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('code_red');
            $table->string('status')->default('0');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes');
        Schema::dropIfExists('customers_stores');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('objectives');
    }
};
