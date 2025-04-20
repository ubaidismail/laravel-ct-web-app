<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $signature = 'migrate:invoices';
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no');
            $table->string('author_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('company_name');
            $table->string('project_name')->nullable();
            $table->string('address');
            $table->string('client_phone');
            $table->string('client_email');
            $table->string('sub_total');
            $table->string('tax_amount');
            $table->integer('tax_rate');
            $table->string('shipping_cost');
            $table->string('total_amount');
            $table->string('client_currency');
            $table->string('invoice_type');
            $table->string('paid_date', 250);
            $table->string('due_date');
            $table->timestamp('paid_date_formatted')->nullable();
            $table->longText('inv_notes');
            $table->integer('status')->default(1);
            $table->string('amount_in_PKR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
