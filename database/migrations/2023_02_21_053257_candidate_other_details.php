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
        Schema::create('candidate_other_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('candidate_id');
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->text('description')->nullable();
            $table->text('file')->nullable();
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
        Schema::dropIfExists('candidate_other_details');
    }
};
