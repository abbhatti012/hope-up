<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->integer('time_taken_seconds');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'quiz_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempts');
    }
}
