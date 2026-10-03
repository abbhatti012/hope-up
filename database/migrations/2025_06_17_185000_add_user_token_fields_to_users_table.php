<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTokenFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_token')->nullable();
            $table->string('fcm_token')->nullable();
            $table->decimal('longitude', 10, 7)->nullable()->default(0);
            $table->decimal('latitude', 10, 7)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_token', 'fcm_token', 'longitude', 'latitude']);
        });
    }
}
