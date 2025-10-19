<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();                         
            $table->unsignedBigInteger('tna_id');
            $table->text('question');
            $table->unsignedTinyInteger('question_number'); 
            $table->timestamps();

            $table->foreign('tna_id')->references('id')->on('tnas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
