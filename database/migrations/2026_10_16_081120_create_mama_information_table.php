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
        Schema::create('mama_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('city_id');
            $table->string('education_file', 400);
            $table->string('national_card_file', 400);
            $table->string('bank_name', 255);
            $table->string('card_number', 16);
            $table->string('iban_number', 24);
            $table->string('image', 400);
            $table->string('image_alt', 400)->nullable();
            $table->string('image_title', 400)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-pending, 1-active, 2-inactive');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mama_information');
    }
};
