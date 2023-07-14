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
        Schema::disableForeignKeyConstraints();

        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->nullable();
            $table->string('subject', 191)->nullable();
            $table->string('email', 60)->nullable();
            $table->text('message')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('task_id')->nullable()->constrained();
            $table->string('try')->default('0');
            $table->string('is_sent')->default('0');
            $table->string('ip', 50)->nullable();
            $table->text('browser')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
