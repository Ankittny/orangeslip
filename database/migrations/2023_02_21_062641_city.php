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
        Schema::create('city', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('districtid');
            $table->integer('state_id');
            $table->text('description')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
           
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
        Schema::dropIfExists('city');
    }
};
