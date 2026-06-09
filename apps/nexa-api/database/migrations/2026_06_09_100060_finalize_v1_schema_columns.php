<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (! Schema::hasColumn('stores', 'logo')) {
                $table->string('logo')->nullable()->after('activity_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'pos_token_hash')) {
                $table->string('pos_token_hash')->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pos_token_hash')) {
                $table->dropColumn('pos_token_hash');
            }
        });

        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'logo')) {
                $table->dropColumn('logo');
            }
        });
    }
};
