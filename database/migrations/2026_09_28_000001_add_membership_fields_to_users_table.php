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
        Schema::table('users', function (Blueprint $table) {
            $table->string('membership_plan')->default('none')->after('password');
            $table->string('membership_status')->default('inactive')->after('membership_plan');
            $table->integer('fitpass_credits')->default(0)->after('membership_status');
            $table->boolean('is_admin')->default(false)->after('fitpass_credits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['membership_plan', 'membership_status', 'fitpass_credits']);
        });
    }
};
