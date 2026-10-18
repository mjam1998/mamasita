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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('spot_player_code');
            $table->string('slug')->unique();
            $table->decimal('price', 15, 0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->string('image', 400);
            $table->string('image_alt', 400)->nullable();
            $table->string('image_title', 400)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
