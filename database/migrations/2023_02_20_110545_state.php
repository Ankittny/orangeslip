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
        Schema::create('state', function (Blueprint $table) {
            $table->bigIncrements('state_id');
            $table->string('state_title');
            $table->string('state_description')->nullable();
            $table->integer('country_id')->default(69);
            $table->string('status')->default('Active');          
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
        Schema::dropIfExists('state');
    }
};
