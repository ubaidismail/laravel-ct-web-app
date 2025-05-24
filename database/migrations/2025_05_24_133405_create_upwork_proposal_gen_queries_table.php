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
        Schema::create('upwork_proposal_gen_queries', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->longText('project_description')->nullable();
            $table->longText('portfolio')->nullable();
            $table->longText('AI_result')->nullable();
            $table->longText('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upwork_proposal_gen_queries');
    }
};
