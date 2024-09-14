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
        Schema::create('candidate_professional_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('candidate_id');
            $table->string('company_name');
            $table->string('job_role');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('candidate_professional_details');
    }
};
