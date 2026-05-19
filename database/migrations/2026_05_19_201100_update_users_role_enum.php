<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altering enum column 'role' to include 'kasir' and 'gudang'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pelanggan', 'kasir', 'gudang') NOT NULL DEFAULT 'pelanggan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum column 'role' to original ['admin', 'pelanggan']
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pelanggan') NOT NULL DEFAULT 'pelanggan'");
    }
};
