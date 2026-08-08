<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_monitoring', function (Blueprint $table) {
            $table->foreignId('purchase_tracker_id')->nullable()->constrained('purchase_trackers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('user_monitoring', function (Blueprint $table) {
            $table->dropForeign(['purchase_tracker_id']);
            $table->dropColumn('purchase_tracker_id');
        });
    }
};