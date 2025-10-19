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
            $table->unsignedBigInteger('user_id');                 
            $table->string('tna_code', 14)->unique();              
            $table->string('name', 255);                          
            $table->string('method', 255);                          
            $table->unsignedTinyInteger('passing_score');            
            $table->year('period');                                 
            $table->string('status', 50);                            
            $table->dateTime('start_date');                       
            $table->dateTime('end_date');                      
            $table->string('speaker', 255);                         
            $table->string('spt_file_path', 255);                    
            $table->enum('realization_status', ['Tidak Realisasi', 'Belum Realisasi', 'Realisasi'])->default('Belum Realisasi');
            $table->timestamps();                                    

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tnas');
    }
};
