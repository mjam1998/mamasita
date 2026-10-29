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
            $table->string('education_file', 400)->nullable();
            $table->string('national_card_file', 400)->nullable();
            $table->decimal('doula_price', 15, 0)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('card_number', 16)->nullable();
            $table->string('iban_number', 24)->nullable();
            $table->string('image', 400)->nullable();
            $table->string('image_alt', 400)->nullable();
            $table->string('image_title', 400)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-completing-information,1-pending, 2-active, 3-inactive,4-rejected');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->text('admin_review')->nullable();
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
