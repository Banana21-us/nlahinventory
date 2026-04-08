<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employment_details', function (Blueprint $table) {
            $table->string('re_membership', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('employment_details', function (Blueprint $table) {
            $table->boolean('re_membership')->default(false)->change();
        });
    }
};
