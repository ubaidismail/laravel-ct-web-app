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
        if (!Schema::hasTable('users')) {

        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id (Primary Key, AUTO_INCREMENT)
        
            $table->string('user_role', 250)->index(); // Index
            $table->string('name', 200);
            $table->string('email', 200)->unique(); 
            $table->string('password', 200);
            $table->string('address', 255);
            $table->string('project', 255);
            $table->decimal('total_amount_paid', 15, 2);
            $table->string('currency', 255);
            $table->text('company');
            $table->text('pass_for_admin_view');
            
            $table->boolean('status')->nullable()->default(0);
        
            // Assuming you're storing timestamps as strings (not recommended), but matching your schema:
            $table->string('created_at', 255);
            $table->string('updated_at', 255);
        });
        }
        

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
