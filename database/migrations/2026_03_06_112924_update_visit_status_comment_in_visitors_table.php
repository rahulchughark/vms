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
        DB::statement("
            ALTER TABLE visitors 
            MODIFY visit_status TINYINT(4) NOT NULL DEFAULT 0 
            COMMENT '0:Pending, 1:Approve, 2:Reject, 3:Completed, 4:In Progress'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE visitors 
            MODIFY visit_status TINYINT(4) NOT NULL DEFAULT 0 
            COMMENT '0:Pending, 1:Approve, 2:Reject, 3:Completed'
        ");
    }
};
