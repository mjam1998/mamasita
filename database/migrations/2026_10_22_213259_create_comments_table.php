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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->morphs('commentable');
            $table->tinyInteger('rate')->default(0)->comment('0,1,2,3,4,5');
            $table->string('body');
            $table->string('admin_response')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-under-review,1-approved,2-rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
