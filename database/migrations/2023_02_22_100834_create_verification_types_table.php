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
        Schema::create('verification_types', function (Blueprint $table) {
            $table->bigIncrements('id');
           
            $table->string('title');
            $table->string('name')->nullable();           
            $table->decimal('amount', $precision = 8, $scale = 2);
             
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_types');
    }
};
