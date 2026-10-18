<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('online_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mama_id');
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->unsignedBigInteger('plan_id');
            $table->tinyInteger('status')->default(0)->comment('0-pay-waiting,1-active,2-completed,3-canceled');
            $table->decimal('total_price', 15, 0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->decimal('pay_amount', 15, 0);
            $table->decimal('mama_share', 15, 0);
            $table->decimal('site_share', 15, 0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('mama_id')->references('id')->on('users');
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('plan_id')->references('id')->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_visits');
    }
};
