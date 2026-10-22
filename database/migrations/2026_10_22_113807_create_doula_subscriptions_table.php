<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doula_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mama_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('city_id');
            $table->tinyInteger('status')->default(0)->comment('0-pay-waiting,1-mama-waiting,2-mom-waiting,3-completed,4-mom-rejected,5-canceled');
            $table->decimal('total_price', 15, 0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->decimal('pay_amount', 15, 0);
            $table->decimal('mama_share', 15, 0);
            $table->decimal('site_share', 15, 0);
            $table->string('mom_mobile_necessary', 11);
            $table->string('user_description')->nullable();
            $table->string('mama_description')->nullable();
            $table->text('address');
            $table->timestamp('doula_date');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('mama_id')->references('id')->on('users');
            $table->foreign('plan_id')->references('id')->on('plans');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doula_subscriptions');
    }
};
