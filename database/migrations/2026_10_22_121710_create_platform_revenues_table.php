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
        Schema::create('platform_revenues', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->comment('1-online-visit,2-monthly-subscription,3-doula-subscription,4-video-course,5-refund');
            $table->tinyInteger('direction')->comment('credit: 1، debit: 2');
            $table->decimal('amount', 15, 0);
            $table->string('description')->nullable();
            $table->nullableMorphs('referenceable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_revenues');
    }
};
