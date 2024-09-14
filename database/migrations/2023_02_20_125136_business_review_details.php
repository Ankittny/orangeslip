<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });
        Schema::create('business_review_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('business_id');
            $table->integer('review')->default(1);

            $table->string('comment');

         
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('business_review_details');
    }
};
