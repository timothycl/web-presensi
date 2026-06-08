<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_approved');
        });

        // Set existing records
        DB::table('users')->where('is_approved', 1)->update(['approval_status' => 'approved']);
        DB::table('users')->where('is_approved', 0)->update(['approval_status' => 'pending']);

        // Drop old column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('approval_status');
        });

        DB::table('users')->where('approval_status', 'approved')->update(['is_approved' => 1]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};
