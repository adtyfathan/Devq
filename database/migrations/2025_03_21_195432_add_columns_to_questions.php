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
        Schema::table('questions', function (Blueprint $table) {
            // $table->integer("point")->default(0)->after("password");
            $table->string("category")->after("question");
            $table->string("difficulty")->after("category");
            $table->json("correct_answers")->after("answers");
            $table->string("explanation")->after("correct_answers");
            $table->dropColumn('correct_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
};