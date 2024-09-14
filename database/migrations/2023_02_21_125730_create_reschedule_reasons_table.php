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
        Schema::create('reschedule_reasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('name')->nullable();
             
            $table->tinyInteger('status')->default(1);
           

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
        Schema::dropIfExists('reschedule_reasons');
    }
};
