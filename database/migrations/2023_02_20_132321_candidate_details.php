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
        Schema::create('candidate_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->string('candidate_code');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->integer('country')->default(69);
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
            
            
           
            $table->string('religion')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('job_role')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('caste')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('area_of_interest')->nullable();
            $table->string('joining_date_prefer')->nullable();
            $table->text('photo')->nullable();
            $table->text('signature')->nullable();
            $table->text('cv_scan')->nullable();   
            $table->integer('added_by');
            $table->integer('hr_id');
            $table->integer('business_id');
            $table->integer('is_selected')->nullable();
            $table->integer('offer_letter_generated')->nullable();
            $table->integer('joining_confirmed')->nullable();
            $table->integer('rating')->nullable();
            $table->string('behaviour')->nullable();
            $table->string('timely_response')->nullable();
            $table->string('communication_skill')->nullable();
            $table->text('review')->nullable();            
            $table->string('status')->default(0)->comment('0-pending, 1- selected, 2- Offer letter generated, 31-Joining Confirmed, 32- Offer Rejected, 4-Reschedule Request');
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
        Schema::dropIfExists('candidate_details');
    }
};
