<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payoff_leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payoff_application_id')->constrained('payoff_applications')->cascadeOnDelete();
            $table->decimal('hours_earned', 6, 2);
            $table->decimal('hours_remaining', 6, 2);
            $table->date('earned_at');
            $table->date('expires_at'); // earned_at + 6 months by exact day
            $table->timestamps();
        });

        Schema::create('payoff_credit_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_id')->constrained('leaves')->cascadeOnDelete();
            $table->foreignId('payoff_leave_credit_id')->constrained('payoff_leave_credits')->cascadeOnDelete();
            $table->decimal('hours_consumed', 6, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payoff_credit_consumptions');
        Schema::dropIfExists('payoff_leave_credits');
    }
};
