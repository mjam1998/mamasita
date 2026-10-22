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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image', '400')->nullable();
            $table->string('image_alt', '400')->nullable();
            $table->string('image_title', '400')->nullable();
            $table->string('meta_description', '400')->nullable();
            $table->string('meta_title', '400')->nullable();
            $table->decimal('price', 15, 0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->decimal('mama_share', 15, 0);
            $table->tinyInteger('type')->comment('1-online-visit,2-monthly-subscription,3-doula-subscription');
            $table->tinyInteger('month_period')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
