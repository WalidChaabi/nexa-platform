<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('reduction')->default(0);
            $table->integer('active')->default(0);
            $table->timestamps();

            $table->index(['store_id', 'campaign_id']);
        });

        Schema::create('campaign_historiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('camp_id');
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->string('code_red');
            $table->dateTime('start_date');
            $table->integer('status')->default(0);
            $table->integer('expired_code')->default(0);
            $table->integer('is_payed')->default(0);
            $table->timestamps();
        });

        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->integer('status')->default(0);
        });

        Schema::create('fonds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->integer('encasement_type')->default(0);
            $table->dateTime('encasement_date');
        });

        Schema::create('initial_fonds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->decimal('value');
            $table->date('date');
        });

        Schema::create('sms_cross_selling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cross_product_id')->constrained('products')->cascadeOnDelete();
            $table->dateTime('sent_date');
            $table->integer('credit');
            $table->integer('status');
            $table->timestamps();
        });

        Schema::create('message_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->foreignId('campaign_detail_id')->constrained('campaign_details')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('messages_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->string('type');
            $table->integer('payed')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->index(['store_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_customers');
        Schema::dropIfExists('message_campaigns');
        Schema::dropIfExists('sms_cross_selling');
        Schema::dropIfExists('initial_fonds');
        Schema::dropIfExists('fonds');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('campaign_historiques');
        Schema::dropIfExists('campaign_details');
    }
};
