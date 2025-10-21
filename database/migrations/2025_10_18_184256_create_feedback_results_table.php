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

            $table->foreignId('registration_id')
                  ->unique()
                  ->constrained('registrations')
                  ->onDelete('cascade');

            $table->timestamps();
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
