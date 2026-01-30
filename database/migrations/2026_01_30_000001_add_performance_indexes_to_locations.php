<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Add performance indexes for frequently filtered/searched columns.
     * 
     * Impact: Speeds up LocationList search/filter and Import duplicate checks.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Index for kode_desa filter in LocationList
            $table->index('kode_desa');
            
            // Index for nama searches (LIKE '%...%' still benefits from index on prefix matches)
            $table->index('nama');
            
            // Index for default sort by created_at
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex(['kode_desa']);
            $table->dropIndex(['nama']);
            $table->dropIndex(['created_at']);
        });
    }
};
