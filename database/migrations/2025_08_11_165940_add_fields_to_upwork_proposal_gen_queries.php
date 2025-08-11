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
        Schema::table('upwork_proposal_gen_queries', function (Blueprint $table) {
            $table->text('conversion_rate')->nullable()->after('AI_result');
            $table->text('AI_model')->nullable()->after('conversion_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upwork_proposal_gen_queries', function (Blueprint $table) {
            //
        });
    }
};
