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
        Schema::create('transaction_wakafs', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('wakaf_uuid');
            $table->uuid('user_uuid')->nullable();
            $table->string('signature')->nullable();
            $table->bigInteger('amount');
            $table->uuid('reference')->nullable();
            $table->enum('status', ['pending', 'cancel', 'rejected', 'success'])->default('pending');
            $table->timestamps();

            $table->foreign("wakaf_uuid")->references("uuid")->on("wakafs")->onDelete("cascade");
            $table->foreign("user_uuid")->references("uuid")->on("users")->onDelete("cascade");
            $table->foreign("reference")->references("uuid")->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_wakafs');
    }
};
