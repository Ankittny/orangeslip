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
        Schema::create('permissions', function (Blueprint $table) {
            $table->integer('ability_id');
            $table->integer('entity_id');
            $table->string('entity_type');
            $table->tinyInteger('forbidden')->default(0);
            $table->integer('scope')->nullable();
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
