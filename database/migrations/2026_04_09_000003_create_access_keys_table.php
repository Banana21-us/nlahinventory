<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // e.g. "HR Access"
            $table->string('description')->nullable();
            $table->string('redirect_to')->nullable();  // route name after login, e.g. 'HR.hrdashboard'
            $table->boolean('is_super')->default(false); // bypasses all gate checks
            $table->json('permissions')->nullable();     // e.g. ["access-hr-only","access-payroll"]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_keys');
    }
};
