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
        Schema::create('reschedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('offer_letter_id');
            $table->date('old_joining_date')->nullable();
            $table->time('old_joining_time')->nullable();
            $table->date('new_joining_date')->nullable();
            $table->time('new_joining_time')->nullable();
            
            $table->string('reason')->nullable();
            $table->integer('hr_response')->default(0)->comment('0-pending, 1- Approve, 2- Reject');
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
        Schema::dropIfExists('reschedule');
    }
};
