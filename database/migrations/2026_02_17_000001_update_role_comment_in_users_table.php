<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role TINYINT NOT NULL DEFAULT 4 COMMENT '1 = admin, 2 = HR, 3 = EMP, 4 = Gate Keeper'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role TINYINT NOT NULL DEFAULT 4 COMMENT '1 = Admin, 2 = Manager, 3 = Employee, 4 = Visitor'");
    }
};
