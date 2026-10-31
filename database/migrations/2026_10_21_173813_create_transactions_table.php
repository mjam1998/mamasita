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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->string('track_number', 10)->unique();
            $table->decimal('amount', 15, 0);
            $table->tinyInteger('direction')->comment('credit: 1، debit: 2، internal: 3');
            $table->tinyInteger('type')->comment('deposit:1, withdraw:2, block:3, unblock:4, settle:5, deposit-blocked:6');
            $table->decimal('total_balance_after', 15, 0);
            $table->decimal('available_balance_after', 15, 0);
            $table->decimal('blocked_balance_after', 15, 0);
            $table->nullableMorphs('referenceable');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('wallet_id')->references('id')->on('wallets');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
