<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index(['user_id', 'email']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->index(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'email']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'name']);
        });
    }
};
