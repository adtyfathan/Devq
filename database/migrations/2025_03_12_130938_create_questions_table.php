<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); 
            $table->string('question');
            $table->string('description');
            $table->json('answers');
            $table->tinyInteger('correct_answer');
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};