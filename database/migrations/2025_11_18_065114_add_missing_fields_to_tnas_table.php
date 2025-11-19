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
        Schema::table('tnas', function (Blueprint $table) {
            $table->string('reason', 255)->nullable()->after('spt_file_path');
            $table->string('goal', 255)->nullable()->after('reason');
            $table->string('before_status', 255)->nullable()->after('goal');
            $table->string('after_status', 255)->nullable()->after('before_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tnas', function (Blueprint $table) {
            $table->dropColumn(['reason', 'goal', 'before_status', 'after_status']);
        });
    }
};
