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
         
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('administrator')->default(1);
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('verification_token')->nullable();
            $table->tinyInteger('is_email_verified')->default(0);
            $table->string('remember_token')->nullable();
            $table->tinyInteger('tnc_accepted')->default(0);
            $table->string('account_type');                       
            $table->decimal('balance', $precision = 10, $scale = 2)->default('0');
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
        Schema::dropIfExists('users');
    }
};
