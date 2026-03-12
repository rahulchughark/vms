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
            $table->tinyInteger('action_type')
                  ->default(0)
                  ->comment('0=None, 1=Cancelled, 2=Reassigned')
                  ->after('approved_by');

            $table->string('reassign_name')
                  ->nullable()
                  ->comment('Reassigned person name')
                  ->after('action_type');

            $table->string('reassign_email')
                  ->nullable()
                  ->comment('Reassigned person email')
                  ->after('reassign_name');

            $table->string('reassign_phone')
                  ->nullable()
                  ->comment('Reassigned person phone number')
                  ->after('reassign_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
     {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn([
                'action_type',
                'reassign_name',
                'reassign_email',
                'reassign_phone',
            ]);
        });
    }
};
