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
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('candidate_id')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->integer('doc_type')->nullable();
            $table->string('doc_name')->nullable();
            $table->text('doc_file')->nullable();
            
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
        Schema::dropIfExists('candidate_documents');
    }
};
