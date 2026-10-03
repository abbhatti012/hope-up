<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionFieldsToPatientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_details', function (Blueprint $table) {
            $table->enum('subscription_status', ['free', 'active', 'expired', 'cancelled'])->default('free');
            $table->enum('subscription_plan', ['free', 'basic', 'premium'])->default('free');
            $table->timestamp('subscription_start_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_trial_active')->default(true);
            $table->decimal('subscription_amount', 10, 2)->nullable();
            $table->string('subscription_payment_method')->nullable();
            $table->string('subscription_transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_details', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'subscription_plan',
                'subscription_start_date',
                'subscription_end_date',
                'trial_ends_at',
                'is_trial_active',
                'subscription_amount',
                'subscription_payment_method',
                'subscription_transaction_id',
            ]);
        });
    }
}
