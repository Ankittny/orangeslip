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
        Schema::create('verification_staffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('department');
            $table->string('pin_code')->nullable();
            $table->string('status')->nullable();
            
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
        Schema::dropIfExists('verification_staffs');
    }
};
