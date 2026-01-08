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
        Schema::create('proposal_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->integer('version_number');
            
            // Copy all fields from proposals table
            $table->string('proposal_name');
            $table->string('prepared_for_customer_name');
            $table->string('client_company_name');
            
            $table->string('prepared_for_customer_email');
            $table->string('prepared_for_customer_phone')->nullable();
            $table->string('prepared_for_customer_address')->nullable();
            $table->longText('proces_briefing')->nullable();
            $table->longText('process_details_in_bullets')->nullable();
            $table->longText('project_description')->nullable();
            $table->decimal('total_project_price', 15, 2)->nullable();
            $table->longText('client_signature')->nullable();
            $table->timestamp('date_signed')->nullable();
            
            // Additional metadata for tracking
            $table->string('change_summary', 500)->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->longText('objective');
            $table->longText('payment_terms');
            $table->string('send_as');
            $table->string('proposal_type');

            // Indexes and constraints
            $table->foreign('proposal_id')
                  ->references('id')
                  ->on('proposals')
                  ->onDelete('cascade');
            
            $table->index('proposal_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_versions');
    }
};
