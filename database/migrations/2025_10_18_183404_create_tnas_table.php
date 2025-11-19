<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tnas', function (Blueprint $table) {
            $table->id();
            $table->string('tna_code', 15)->unique();
            $table->string('name', 255);
            $table->string('method', 255);
            $table->unsignedTinyInteger('passing_score');
            $table->year('period');
            $table->unsignedTinyInteger('batch')->default(1);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('speaker', 255);
            $table->string('spt_file_path', 255)->nullable(); 
            $table->enum('realization_status', ['belum terealisasi', 'terealisasi', 'tidak terealisasi'])->default('belum terealisasi');

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tnas');
    }
};