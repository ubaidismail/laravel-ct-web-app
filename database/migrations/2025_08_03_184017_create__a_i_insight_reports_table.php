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
        Schema::create('AI_insight_reports', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->json('data');
            $table->timestamp('generated_at');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['generating', 'completed', 'failed'])->default('generating');
            $table->timestamps();
            
            $table->index(['type', 'generated_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AI_insight_reports');
    }
};
