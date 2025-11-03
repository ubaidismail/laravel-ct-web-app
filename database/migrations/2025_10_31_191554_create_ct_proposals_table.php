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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('prepared_for_customer_name');
            $table->string('prepared_for_customer_email');
            $table->string('prepared_for_customer_phone')->nullable();
            $table->string('prepared_for_customer_address')->nullable();
            $table->longText('proces_briefing')->nullable();
            $table->longText('process_details_in_bullets')->nullable;
            $table->longText('project_description')->nullable();
            $table->decimal('total_project_price', 15, 2)->nullable();
            $table->longText('client_signature')->nullable();
            $table->timestamp('date_signed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
