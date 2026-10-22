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
        Schema::create('course_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_price', 15, 0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->decimal('pay_amount', 15, 0);
            $table->tinyInteger('status')->default(0)->comment('0-pay-waiting,1-completed,2-canceled');
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_orders');
    }
};
