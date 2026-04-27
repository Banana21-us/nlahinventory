<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['item_type_id', 'sku']);

            // Core identity columns
            $table->string('asset_code', 50)->unique()->after('id');
            $table->string('name')->after('asset_code');
            $table->string('category', 100)->nullable()->after('name');

            // Department & location (proper FKs replacing the bare integers)
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete()->after('category');
            $table->foreignId('maintenance_department_id')->nullable()->constrained('departments')->nullOnDelete()->after('department_id');

            // location_id already exists — make it a proper FK
            $table->foreignId('location_id')->nullable()->change();

            // Extra asset details
            $table->string('model', 100)->nullable()->after('brand');
            $table->string('serial_number', 100)->nullable()->after('model');
            $table->decimal('purchase_cost', 10, 2)->nullable()->after('purchase_date');
            $table->integer('lifespan_years')->nullable()->after('purchase_cost');
            $table->date('end_of_life')->nullable()->after('lifespan_years');

            // Replace status string with enum
            $table->enum('status', ['active', 'in_use', 'maintenance', 'retired'])->default('active')->change();

            $table->enum('condition_status', ['good', 'fair', 'poor'])->default('good')->after('status');
            $table->text('notes')->nullable()->after('condition_status');
            $table->string('image')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['maintenance_department_id']);
            $table->dropColumn([
                'asset_code', 'name', 'category',
                'department_id', 'maintenance_department_id',
                'model', 'serial_number', 'purchase_cost',
                'lifespan_years', 'end_of_life',
                'condition_status', 'notes', 'image',
            ]);

            $table->unsignedBigInteger('item_type_id');
            $table->string('sku')->unique();
            $table->string('status')->default('available')->change();
        });
    }
};
