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
    Schema::create('purchase_trackers', function (Blueprint $table) {

        $table->id();

        $table->string('purachase_name')->unique();

        $table->date('purchase_date');

        $table->enum('company', ['NEBG', 'FA']);

        $table->string('vendor');

        $table->string('receipt_number')->nullable();

        $table->date('receipt_date')->nullable();

        $table->text('receipt_details')->nullable();

        $table->decimal('grand_total', 12, 2)->default(0);

        $table->text('remarks')->nullable();

        $table->string('receipt_encoded_by');

        $table->string('received_by')->nullable();

        $table->string('pickup_by')->nullable();

        $table->string('bought_by')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_trackers');
    }
};
