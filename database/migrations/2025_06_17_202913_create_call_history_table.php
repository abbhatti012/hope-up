<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('call_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('callee_id')->constrained('users')->onDelete('cascade');
            $table->string('call_type')->default('voice'); // voice or video
            $table->string('status')->default('initiated'); // initiated, accepted, rejected, ended, missed
            $table->integer('duration')->nullable(); // duration in seconds
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('call_history');
    }
}
