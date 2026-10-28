<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbook_entries', function (Blueprint $table) {
            $table->enum('tipe', ['hadir', 'izin', 'sakit'])->default('hadir')->after('user_id');
            $table->enum('status', ['menunggu', 'disetujui'])->default('disetujui')->after('tipe');
            $table->decimal('latitude', 10, 7)->nullable()->after('bukti');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->time('jam_mulai')->nullable()->change();
            $table->time('jam_selesai')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('logbook_entries', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'status', 'latitude', 'longitude']);
        });
    }
};