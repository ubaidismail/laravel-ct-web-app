<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->timestamp('paid_date_formatted')->nullable();
        });
         // Step 2: Convert and copy data
         $invoices = DB::table('invoices')->select('id', 'paid_date')->get();

         foreach ($invoices as $invoice) {
             try {
                 $parsedDate = Carbon::createFromFormat('M d Y - H:i:s', $invoice->paid_date);
 
                 DB::table('invoices')
                     ->where('id', $invoice->id)
                     ->update(['paid_date_formatted' => $parsedDate]);
             } catch (\Exception $e) {
                 // Log or skip invalid formats
                 // echo "Invalid date: " . $invoice->paid_date . "\n";
             }
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('paid_date_formatted');
        });
    }
};
