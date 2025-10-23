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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('employment_status', [
                'NA',
                'active',
                'probation',
                'notice_period',
                'contract',
                'intern',
                'trainee',
                'on_leave',
                'unpaid_leave',
                'laid_off',
                'contract_ended',
                'resigned',
                'terminated',
                'absconded',
                'retired',
                'deceased',
                ])->default('NA')->after('user_role');

                $table->date('employment_start_date')->nullable()->after('employment_status');
                $table->date('employment_end_date')->nullable()->after('employment_start_date');
                $table->decimal('current_salary', 10, 2)->nullable()->after('employment_end_date');
                $table->string('project', 255)->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
