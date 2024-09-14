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
        Schema::create('abilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->integer('entity_id')->nullable();
            $table->string('entity_type')->nullable();
            $table->tinyInteger('only_owned')->default(0);          
            $table->longText('options')->nullable();          
            $table->integer('scope')->nullable();          
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
        Schema::dropIfExists('abilities');
    }
};
