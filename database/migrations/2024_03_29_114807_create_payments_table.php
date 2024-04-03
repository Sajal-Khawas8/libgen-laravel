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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('r_payment_id')->unique();
            $table->string('method');
            $table->foreignUuid('user_id')->references('uuid')->on('users')->cascadeOnUpdate()->noActionOnDelete();
            $table->string('amount');
            $table->integer('payment_type');
            $table->foreign("payment_type")
                ->references("id")
                ->on("payment_types")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->longText('json_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
