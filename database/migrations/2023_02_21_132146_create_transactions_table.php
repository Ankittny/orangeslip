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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('currency_id')->default(1);
            
            $table->string('reference_no')->nullable();
            $table->enum('type', ['Credit', 'Debit']);

            $table->string('source')->nullable();
            $table->string('description')->nullable();
            $table->unsignedDecimal('amount', $precision = 10, $scale = 2);
            $table->unsignedDecimal('updated_balance', $precision = 10, $scale = 2);

            $table->date('payment_confirmation_date')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('transaction_id')->nullable();
           
           

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
