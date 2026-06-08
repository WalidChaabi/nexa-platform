<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            if (! Schema::hasColumn('users', 'store_id')) {
                $table->unsignedInteger('store_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('users', 'is_admin')) {
                $table->unsignedTinyInteger('is_admin')->default(3)->after('store_id');
            }

            if (! Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname')->default('')->after('is_admin');
            }

            if (! Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname')->default('')->after('firstname');
            }

            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('lastname');
            }

            if (! Schema::hasColumn('users', 'allow_share')) {
                $table->unsignedTinyInteger('allow_share')->default(0)->after('password');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->unsignedTinyInteger('status')->default(1)->after('allow_share');
            }

            if (! Schema::hasColumn('users', 'is_active')) {
                $table->unsignedTinyInteger('is_active')->default(1)->after('status');
            }

            if (! Schema::hasColumn('users', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'store_id',
                'is_admin',
                'firstname',
                'lastname',
                'phone',
                'allow_share',
                'status',
                'is_active',
                'last_activity',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (! Schema::hasColumn('users', 'name')) {
                $table->string('name')->default('')->after('id');
            }
        });
    }
};
