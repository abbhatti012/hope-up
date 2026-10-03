<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('phone_number')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->longText('about')->nullable();
            $table->string('speciality')->nullable();
            $table->string('medical_license')->nullable();
            $table->integer('experience')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('degree_certificate')->nullable();
            $table->bigInteger('speciality_id')->unsigned();
            $table->timestamps();

            $table->index('user_id');
            $table->index('speciality_id');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('speciality_id')->references('id')->on('manage_speciality')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_details');
    }
}
