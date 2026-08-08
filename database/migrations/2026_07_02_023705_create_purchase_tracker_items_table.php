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
    Schema::create('purchase_tracker_items', function (Blueprint $table) {

        $table->id();

        $table->foreignId('purchase_tracker_id')
              ->constrained()
              ->onDelete('cascade');

        $table->string('description');

        $table->decimal('unit_price',12,2);

        $table->integer('quantity');

        $table->decimal('subtotal',12,2);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_tracker_items');
    }
};
