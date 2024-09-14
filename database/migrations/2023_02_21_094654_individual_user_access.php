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
        Schema::create('individual_user_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');            
            $table->string('access_id');            
            
            
            $table->integer('access_status')->default(0);            
            
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
        Schema::dropIfExists('individual_user_access');
    }
};
