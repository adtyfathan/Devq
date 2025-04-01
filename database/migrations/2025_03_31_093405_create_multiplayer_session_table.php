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
        Schema::create('multiplayer_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->char('session_code', 6);
            $table->enum('status', ['waiting', 'in_progress', 'completed'])->default('waiting');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiplayer_session');
    }
};