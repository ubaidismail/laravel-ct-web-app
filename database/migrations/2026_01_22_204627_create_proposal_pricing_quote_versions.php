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
        Schema::create('proposal_pricing_quote_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_version_id');
            $table->integer('version_number');
            $table->string('timeline');
            $table->string('services');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 15, 2);
        
            $table->foreign('proposal_version_id')
                  ->references('id')
                  ->on('proposal_versions')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_pricing_quote_versions');
    }
};
