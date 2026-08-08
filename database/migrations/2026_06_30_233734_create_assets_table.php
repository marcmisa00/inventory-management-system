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
    Schema::create('assets', function (Blueprint $table) {

        $table->id();

        $table->string('asset_tag')->unique();

        $table->string('delivery_date');

        $table->string('category');

        $table->string('brand')->nullable();

        $table->string('provider')->nullable();

        $table->string('status');

        $table->text('specification')->nullable();

        $table->text('remarks')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
