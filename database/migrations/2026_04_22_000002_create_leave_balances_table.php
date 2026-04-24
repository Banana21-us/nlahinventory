<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->decimal('total', 8, 2)->default(0);
            $table->decimal('consumed', 8, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'leave_type_id']);
        });

        // Migrate existing payroll_and_leaves data into the new normalised table.
        // Maps payroll column prefix → LeaveType code.
        $keyToCode = [
            'vl' => 'VL',
            'sl' => 'SL',
            'bl' => 'BL',
            'spl' => 'SPL',
            'el' => 'EL',
            'ml' => 'ML',
            'pl' => 'PL',
            'syl' => 'SYL',
            'cal' => 'CAL',
            'stl' => 'STL',
            'mwl' => 'MWL',
        ];

        // Build code → id lookup. Skip codes not yet seeded.
        $typeIds = DB::table('leave_types')
            ->whereIn('code', array_values($keyToCode))
            ->pluck('id', 'code')
            ->toArray();

        $now = now();

        DB::table('payroll_and_leaves')
            ->whereNotNull('user_id')
            ->orderBy('id')
            ->each(function ($row) use ($keyToCode, $typeIds, $now) {
                $inserts = [];

                foreach ($keyToCode as $key => $code) {
                    $typeId = $typeIds[$code] ?? null;
                    if (! $typeId) {
                        continue;
                    }

                    $total = (float) ($row->{$key.'_total'} ?? 0);
                    $consumed = (float) ($row->{$key.'_consumed'} ?? 0);

                    $inserts[] = [
                        'user_id' => $row->user_id,
                        'leave_type_id' => $typeId,
                        'total' => $total,
                        'consumed' => $consumed,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (! empty($inserts)) {
                    DB::table('leave_balances')->insertOrIgnore($inserts);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
