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
        Schema::create('course_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->tinyInteger('p0_all_device')->default(0);
            $table->tinyInteger('p1_windows')->default(0);
            $table->tinyInteger('p2_macos')->default(0);
            $table->tinyInteger('p3_linux')->default(0);
            $table->tinyInteger('p4_android')->default(0);
            $table->tinyInteger('p5_ios')->default(0);
            $table->tinyInteger('p6_webapp')->default(0);
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_limits');
    }
};
