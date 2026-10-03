<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->string('payment_method');
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3);
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded', 'cancelled']);
            $table->text('notes')->nullable();
            $table->enum('source', ['appointment', 'subscription'])->nullable();
            $table->decimal('doctor_amount', 10, 2)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
