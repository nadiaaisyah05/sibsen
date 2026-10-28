<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE logbook_entries MODIFY status ENUM('menunggu','disetujui','ditolak') DEFAULT 'disetujui'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE logbook_entries MODIFY status ENUM('menunggu','disetujui') DEFAULT 'disetujui'");
    }
};