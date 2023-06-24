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
        Schema::table('wakafs', function (Blueprint $table) {
            $table->enum('status', ['active', 'done', 'not active'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wakafs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
