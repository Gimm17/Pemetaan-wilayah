<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Drop fid column
            $table->dropColumn('fid');
            
            // Add kode_desa column after id
            $table->string('kode_desa', 50)->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Restore fid column
            $table->unsignedInteger('fid')->nullable()->after('id');
            
            // Drop kode_desa column
            $table->dropColumn('kode_desa');
        });
    }
};
