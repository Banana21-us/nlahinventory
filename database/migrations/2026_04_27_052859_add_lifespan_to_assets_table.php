<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'lifespan_years')) {
                $table->integer('lifespan_years')->nullable()->after('purchase_cost');
            }
            if (!Schema::hasColumn('assets', 'end_of_life')) {
                $table->date('end_of_life')->nullable()->after('lifespan_years');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['lifespan_years', 'end_of_life']);
        });
    }
};
