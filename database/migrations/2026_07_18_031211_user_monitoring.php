<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_monitoring', function (Blueprint $table) {
            $table->id();
            
            // PC Information
            $table->string('pc_name');
            $table->string('serial_number')->unique();
            $table->string('idno')->nullable(); // Employee ID from HRIS
            $table->string('department')->nullable();
            $table->string('job_title')->nullable();
            $table->string('location')->nullable();
            $table->string('set_up')->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            
            // Hardware Components
            $table->string('motherboard')->nullable();
            $table->string('processor')->nullable();
            $table->string('hdd')->nullable();
            $table->string('ssd')->nullable();
            $table->string('ram')->nullable();
            $table->string('psu')->nullable();
            $table->string('cpuf')->nullable();
            $table->string('monitor')->nullable();
            $table->string('keyboard')->nullable();
            $table->string('mouse')->nullable();
            $table->string('avr')->nullable();
            $table->string('binaural')->nullable();
            $table->string('monaural')->nullable();
            $table->string('magic_jack')->nullable();
            $table->string('headset')->nullable();
            $table->string('camera')->nullable();
            $table->string('dialpad')->nullable();
            $table->string('ups')->nullable();
            $table->string('telephone_set')->nullable();
            
            // Network Information
            $table->string('ip_address')->nullable();
            $table->string('vpn')->nullable();
            
            // Software Information
            $table->string('operating_system')->nullable();
            $table->string('product_key')->nullable();
            $table->string('microsoft_office')->nullable();
            $table->string('office_serial_key')->nullable();
            $table->string('microsoft_account')->nullable();
            
            // Purchase Information
            $table->date('delivery_date')->nullable();
            $table->decimal('pc_cost', 12, 2)->default(0);
            $table->string('store')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_monitoring');
    }
};