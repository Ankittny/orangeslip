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
        Schema::create('candidate_bulk_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('job_role')->nullable();
            $table->string('total_experience')->nullable();

         
            $table->integer('added_by')->nullable();
            $table->integer('hr_id')->nullable();
            $table->integer('business_id')->nullable();
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
        Schema::dropIfExists('candidate_bulk_data');
    }
};