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
        Schema::create('admin_pays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_pay_id')->nullable();
            $table->string('track_number', 10)->unique();
            $table->decimal('amount', 15, 0);
            $table->tinyInteger('type')->comment('1-mama-share,2-refund');
            $table->tinyInteger('status')->comment('0-waiting,1-paid,2-canceled');
            $table->string('bank_name');
            $table->string('iban_number', 24);
            $table->string('card_number', 16);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_pay_id')->references('id')->on('user_pays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_pays');
    }
};
