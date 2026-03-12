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
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('card_number')
                  ->nullable()
                  ->comment('Visitor entry card number')
                  ->after('approved_by');

            $table->date('exit_date')
                  ->nullable()
                  ->comment('Visitor exit date')
                  ->after('card_number');

            $table->time('exit_time')
                  ->nullable()
                  ->comment('Visitor exit time')
                  ->after('exit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['card_number', 'exit_date', 'exit_time']);
        });
    }
};
