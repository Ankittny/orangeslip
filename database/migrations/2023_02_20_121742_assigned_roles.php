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
        Schema::create('assigned_roles', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('entity_id');
            $table->string('entity_type');
            $table->integer('restricted_to_id')->nullable();
            $table->string('restricted_to_type')->nullable();  
            $table->integer('scope')->nullable();        
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
        Schema::dropIfExists('assigned_roles');
    }
};
