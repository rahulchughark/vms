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
        Schema::create('visitors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('mobile_no');
                $table->string('profile_image')->nullable();
                $table->string('visitor_id_proof')->nullable();
                $table->string('company_id');
                $table->string('meet_person_name');
                $table->string('meet_person_email');
                $table->string('purpose');
                $table->date('visit_date');
                $table->time('approx_total_time');
                $table->time('in_time');
                $table->tinyInteger('status')->default(0)->comment('0 = Pending, 1 = Completed');
                $table->tinyInteger('is_deleted')->default(0)->comment('0 = Active, 1 = Deleted');
                // $table->foreignId('created_by')->constrained('users'); // logged in user
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
