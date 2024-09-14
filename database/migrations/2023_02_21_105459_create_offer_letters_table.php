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
        Schema::create('offer_letters', function (Blueprint $table) {
            $table->bigIncrements('id');
                  
            $table->integer('candidate_id');            
            $table->integer('hr_id');            
            $table->integer('business_id');    

             

            $table->string('post')->nullable();    

            $table->date('joining_date')->nullable();            
            $table->string('place_of_joining')->nullable();            
            $table->time('time_of_joining')->nullable();            
               
            $table->decimal('annual_ctc', $precision = 10, $scale = 2);          
            $table->text('salary_breakup')->nullable();            
            $table->text('offer_letter')->nullable();            
            $table->integer('is_accepted')->default(0)->comment('1-Accept, 2- Reject, 3- Reschedule Request');            
            $table->string('rejected_reason')->nullable();            
            $table->integer('is_rescheduled')->default(0);            
            $table->integer('joining_confirmed')->default(0);            
            $table->tinyInteger('is_checked')->default(0);            
            
            
            
            
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
        Schema::dropIfExists('offer_letters');
    }
};
