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
        Schema::create('enroll_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('business_name');            
            $table->string('email');            
            $table->string('owner_first_name');            
            $table->string('owner_last_name');            
            $table->string('mobile_no');            
            $table->integer('no_of_employee')->nullable();            
            $table->integer('is_verified')->default(0);            
            $table->integer('is_created')->default(0);            
            $table->integer('verifier_id')->nullable();            
            $table->integer('creator_id')->nullable();            
            $table->string('reason')->nullable();            
            $table->integer('status')->default(1); 
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
        Schema::dropIfExists('enroll_companies');
    }
};
