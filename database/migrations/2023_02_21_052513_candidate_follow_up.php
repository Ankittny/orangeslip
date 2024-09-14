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
        Schema::create('candidate_follow_up', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('candidate_id');
            $table->integer('hr_id')->nullable();
            $table->date('date')->nullable();
            $table->string('remarks')->nullable();
            $table->date('next_contact_date')->nullable();
            $table->time('next_time')->nullable();            
            $table->tinyInteger('status')->nullable();    
            
            
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
        Schema::dropIfExists('candidate_follow_up');
    }
};
