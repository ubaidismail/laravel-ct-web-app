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
        Schema::create('sales_target_of_reps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rep_id');
            $table->foreign('rep_id')->references('id')->on('users');
            $table->string('rep_name')->nullable();
            $table->decimal('target', 10, 2);
            $table->decimal('mtd_sales', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_target_of_reps');
    }
};
