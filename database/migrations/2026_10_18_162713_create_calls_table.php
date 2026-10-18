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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_subscription_id');
            $table->tinyInteger('status')->default(0)->comment('0-mama-waiting,1-mom-waiting,2-approved,3-rejected');
            $table->decimal('pay_amount', 15, 0);
            $table->tinyInteger('rate')->nullable()->comment('0,1,2,3,4,5');
            $table->text('user_comment')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('expire_at');
            $table->timestamps();
            $table->foreign('monthly_subscription_id')->references('id')->on('monthly_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
