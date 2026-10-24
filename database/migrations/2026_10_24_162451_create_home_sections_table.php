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
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('image', '400');
            $table->string('image_alt', '400')->nullable();
            $table->string('image_title', '400')->nullable();
            $table->string('meta_description', '400')->nullable();
            $table->string('meta_title', '400')->nullable();
            $table->string('h1_text');
            $table->string('h2_text');
            $table->string('h3_text');
            $table->string('btn_text');
            $table->string('btn_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
