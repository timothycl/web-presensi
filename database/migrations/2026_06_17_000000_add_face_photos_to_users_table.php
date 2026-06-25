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
            $table->string('face_photo_front')->nullable()->after('photo');
            $table->string('face_photo_right')->nullable()->after('face_photo_front');
            $table->string('face_photo_left')->nullable()->after('face_photo_right');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['face_photo_front', 'face_photo_right', 'face_photo_left']);
        });
    }
};
