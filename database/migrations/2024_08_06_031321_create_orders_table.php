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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('date')->useCurrent();
            $table->string('number');
            $table->enum('payment', ['cash', 'transfer'])->default('cash');
            $table->string('trx_id')->nullable();
            $table->enum('status', ['done', 'cancel'])->default('done');
            $table->string('cancel_reason')->nullable();
            $table->integer('ppn')->default(0);
            $table->bigInteger('total')->default(0);
            $table->bigInteger('bill')->default(0);
            $table->bigInteger('return')->default(0);
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
