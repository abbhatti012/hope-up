<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->text('comments')->nullable();
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->enum('rating_type', ['excellent', 'very_good', 'good', 'fair', 'poor', 'bad'])->default('good');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->index(['doctor_id', 'is_approved']);
            $table->index(['reviewer_id']);
            $table->index(['rating']);
            $table->unique(['doctor_id', 'reviewer_id'], 'unique_doctor_reviewer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_reviews');
    }
}
