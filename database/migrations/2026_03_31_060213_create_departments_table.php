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
        Schema::create('departments', function (Blueprint $table) {
        $table->id(); // Primary Key
        $table->string('name'); // e.g., "Nursing", "MIS", "Accounting"
        $table->string('code')->unique(); // e.g., "NSG", "MIS", "ACCT"
        
        // We use integer() instead of foreignId() to avoid strict database locks
        $table->integer('dept_head_id')->nullable(); 
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
