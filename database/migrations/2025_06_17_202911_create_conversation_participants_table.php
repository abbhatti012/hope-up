<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationParticipantsTable extends Migration
{
    public function up()
    {
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversation_participants');
    }
}
