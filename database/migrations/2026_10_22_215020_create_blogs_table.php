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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', '300');
            $table->string('slug', '300')->unique();
            $table->text('description');
            $table->string('image', '400')->nullable();
            $table->string('image_alt', '400')->nullable();
            $table->string('image_title', '400')->nullable();
            $table->string('meta_description', '400')->nullable();
            $table->string('meta_title', '400')->nullable();
            $table->string('keywords', '400')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
