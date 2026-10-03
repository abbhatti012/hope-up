<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('question_text');
            $table->integer('sequence_number'); // Order of the question in the quiz
            $table->string('type')->default('multiple_choice'); // multiple_choice, true_false, etc.
            $table->integer('points')->default(1);
            $table->text('explanation')->nullable();
            $table->timestamps();

            $table->unique(['quiz_id', 'sequence_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
