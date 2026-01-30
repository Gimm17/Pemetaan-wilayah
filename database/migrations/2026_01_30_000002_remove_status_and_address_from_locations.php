<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Remove status and address columns from locations table.
     * These fields are not used in Excel import and are unnecessary for the application.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['status', 'address']);
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('category_id');
            $table->text('address')->nullable()->after('longitude');
        });
    }
};
