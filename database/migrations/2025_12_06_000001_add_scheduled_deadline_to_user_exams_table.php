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
        Schema::table('user_exams', function (Blueprint $table) {
            // Tambah kolom untuk deadline ujian
            $table->dateTime('scheduled_deadline')->nullable()->after('finished_at')->comment('Batas waktu user harus mengerjakan ujian');
            // Tambah kolom untuk tracking notifikasi sudah dikirim
            $table->boolean('notification_sent')->default(false)->after('scheduled_deadline')->comment('Flag jika notifikasi sudah dikirim ke admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_exams', function (Blueprint $table) {
            $table->dropColumn(['scheduled_deadline', 'notification_sent']);
        });
    }
};
