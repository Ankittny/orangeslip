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
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('verification_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->string('doc_name')->nullable();
            $table->string('doc_file')->nullable();
            $table->integer('doc_type')->comment('1-Assign, 2- Report');

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
        Schema::dropIfExists('verification_documents');
    }
};
