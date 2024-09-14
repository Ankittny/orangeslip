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
        Schema::create('deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->nullable();            
            $table->decimal('amount', $precision = 10, $scale = 2);
            $table->string('tid')->nullable();
            $table->text('doc')->nullable();
            $table->string('comment')->nullable();
            $table->integer('status')->default(1);
            $table->string('reason')->nullable();
           
           
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
        Schema::dropIfExists('deposits');
    }
};
