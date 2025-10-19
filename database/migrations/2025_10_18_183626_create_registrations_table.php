<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();                                 
            $table->unsignedBigInteger('user_id');         
            $table->unsignedBigInteger('tna_id');          
            $table->string('password', 255);             
            $table->enum('role', ['tidak lulus', 'lulus'])->default('tidak lulus');              
            $table->timestamps();                           

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tna_id')->references('id')->on('tnas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
