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
            $table->date('regist_date');
            $table->enum('status', ['lulus', 'tidak lulus'])->default('tidak lulus');
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('tna_id')
                  ->constrained('tnas')
                  ->onDelete('cascade');

            $table->timestamps();
            $table->unique(['user_id', 'tna_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};