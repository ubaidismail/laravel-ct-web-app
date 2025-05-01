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
        Schema::create('reward_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_user_id');
            $table->foreign('referrer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('token_id');
            $table->foreign('token_id')->references('id')->on('referal_token')->onDelete('cascade');
            $table->string('amount');
            $table->string('referal_type');
            $table->datetime('issued_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_points');
    }
};
