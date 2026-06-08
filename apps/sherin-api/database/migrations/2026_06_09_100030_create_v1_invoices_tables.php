<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employ_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->integer('for_sexe')->default(0);
            $table->unsignedTinyInteger('description')->default(0);
            $table->dateTime('invoice_date');
            $table->float('total')->default(0);
            $table->float('total_ht')->nullable()->default(0);
            $table->float('tva')->nullable()->default(0);
            $table->float('paid_amount')->default(0);
            $table->unsignedTinyInteger('payment_method')->default(0);
            $table->integer('points')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->float('reste')->nullable()->default(0);
            $table->float('reste_payer')->nullable()->default(0);

            $table->index('store_id');
            $table->index('customer_id');
            $table->index('invoice_date');
        });

        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('qte');
            $table->float('price');

            $table->index('invoice_id');
            $table->index('product_id');
        });

        Schema::create('invoices_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->float('amount');
            $table->dateTime('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices_paiements');
        Schema::dropIfExists('invoice_products');
        Schema::dropIfExists('invoices');
    }
};
