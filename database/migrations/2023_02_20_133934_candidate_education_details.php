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
        Schema::create('candidate_education_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('candidate_id');
            $table->text('institute_name');
            $table->text('degree');
            $table->string('year_of_passing');
            $table->decimal('marks', $precision = 10, $scale = 2);
            $table->decimal('percentage', $precision = 10, $scale = 2);
            
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
        Schema::dropIfExists('candidate_education_details');
    }
};
