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
        Schema::table('project_quotes', function (Blueprint $table) {
            // customer_id
            $table->foreignId('customer_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending')->after('project_requirements');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_quotes', function (Blueprint $table) {
            //
        });
    }
};
