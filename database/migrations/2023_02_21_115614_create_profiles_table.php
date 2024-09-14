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
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('mobile_no')->nullable();
            $table->enum('gender', ['male', 'female','others'])->nullable();
            $table->string('maritial_status')->nullable();
            $table->string('religion')->nullable();
            $table->date('dob')->nullable();
            $table->string('age')->nullable();
            $table->string('nationality')->nullable();
            $table->string('designations')->nullable();
            $table->string('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('avatar')->nullable();
            $table->tinyInteger('two_fa_status')->default(0);
            $table->text('two_fa_code')->nullable();
            $table->longText('two_factor_image_url')->nullable();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->dateTime('first_login_time')->nullable();
            $table->integer('last_checked_notification_id')->nullable();
            $table->string('aadhar_card_no')->nullable();
            $table->tinyInteger('aadhar_verify')->default(0);
            $table->string('voter_no')->nullable();
            $table->tinyInteger('voter_verify')->default(0);
            $table->string('pancard_no')->nullable();
            $table->tinyInteger('pancard_verify')->default(0);
            $table->string('passport_no')->nullable();
            $table->tinyInteger('passport_verify')->default(0);
            $table->string('driving_license_no')->nullable();
            $table->tinyInteger('driving_license_verify')->default(0);
            $table->tinyInteger('kyc_verified')->nullable();
            // $table->unsignedDecimal('wallet_balance', $precision = 8, $scale = 2)->default(0);
           

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
        Schema::dropIfExists('profiles');
    }
};
