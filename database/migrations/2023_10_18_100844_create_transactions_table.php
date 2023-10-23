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
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount');
            $table->decimal('charge');
            $table->decimal('total_amount');
            $table->string('from_account_number');
            $table->string('from_account_name');
            $table->string('to_account_number');
            $table->string('to_account_name');
            $table->string('reference');
            $table->enum('type', ['credit', 'debit']);
            $table->dateTime('time_completed');
            $table->timestamps();
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
