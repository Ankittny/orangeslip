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
        Schema::create('business_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('business_name');
            $table->date('registration_date')->nullable();
            $table->text('registration_doc')->nullable();
            $table->string('business_address')->nullable();
            $table->integer('no_of_employee');
            $table->string('contact_persons')->nullable();
            $table->text('logo')->nullable();
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
        Schema::dropIfExists('business_details');
    }
};
