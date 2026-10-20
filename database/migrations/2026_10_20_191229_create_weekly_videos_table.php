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
        Schema::create('weekly_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_subscription_id');
            $table->unsignedBigInteger('video_id');
            $table->tinyInteger('rate')->nullable()->comment('0,1,2,3,4,5');
            $table->text('user_comment')->nullable();
            $table->boolean('is_seen')->default(false);
            $table->timestamp('show_date');
            $table->timestamps();
            $table->foreign('monthly_subscription_id')->references('id')->on('monthly_subscriptions');
            $table->foreign('video_id')->references('id')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_videos');
    }
};
