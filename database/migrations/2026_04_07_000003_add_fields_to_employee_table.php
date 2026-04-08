<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->string('contact_relationship')->nullable()->after('contact_number');
            $table->string('signature')->nullable()->after('contact_relationship');
            $table->string('picture')->nullable()->after('signature');
            $table->string('id_no')->nullable()->after('picture');
        });
    }

    public function down(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->dropColumn(['contact_relationship', 'signature', 'picture', 'id_no']);
        });
    }
};
