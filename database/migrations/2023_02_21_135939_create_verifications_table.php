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
        Schema::create('verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('candidate_id')->nullable();
            $table->integer('hr_id')->nullable();
            $table->integer('staff_id')->nullable();

             
            $table->enum('verification_type', ['Personal', 'Educational','Professional','Legal']);
            $table->tinyInteger('is_assigned')->nullable();
            $table->integer('status')->default(1)->comment('1-pending, 2-assign to staff,3-verified,4-unverified,5- reject request');

            $table->text('details')->nullable();
            $table->text('document')->nullable();
            
            $table->tinyInteger('updated_to_hr')->nullable();

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
        Schema::dropIfExists('verifications');
    }
};
