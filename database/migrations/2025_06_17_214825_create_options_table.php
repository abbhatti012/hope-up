<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('sequence_number'); // Order of the option
            $table->timestamps();

            $table->unique(['question_id', 'sequence_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
}
