<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('registration_id')->unique();
            $table->date('feedback_date');

            $table->unsignedTinyInteger('score_01');
            $table->unsignedTinyInteger('score_02');
            $table->unsignedTinyInteger('score_03');
            $table->unsignedTinyInteger('score_04');
            $table->unsignedTinyInteger('score_05');
            $table->unsignedTinyInteger('score_06');
            $table->unsignedTinyInteger('score_07');
            $table->unsignedTinyInteger('score_08');
            $table->unsignedTinyInteger('score_09');
            $table->unsignedTinyInteger('score_10');
            $table->unsignedTinyInteger('score_11');
            $table->unsignedTinyInteger('score_12');
            $table->unsignedTinyInteger('score_13');
            $table->unsignedTinyInteger('score_14');
            $table->unsignedTinyInteger('score_15');

            $table->timestamps();

            $table->foreign('registration_id')->references('id')->on('registrations');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_results');
    }
};
