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
        Schema::table('reward_point', function (Blueprint $table) {
            $table->string('percent_markup')->nullable()->after('referal_type');
            $table->string('status')->default('pending')->after('percent_markup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_point', function (Blueprint $table) {
            //
        });
    }
};
