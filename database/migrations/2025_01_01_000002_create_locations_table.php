<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fid')->nullable();
            $table->string('shape')->nullable();
            $table->string('nama')->nullable();

            $table->string('nop')->nullable();
            $table->unique('nop');

            $table->decimal('luas', 14, 2)->nullable();
            $table->string('sertpikat')->nullable();
            $table->decimal('njop', 14, 2)->nullable();
            $table->decimal('luas_bangu', 14, 2)->nullable();
            $table->string('user_perum')->nullable();

            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->text('address')->nullable();

            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status','category_id']);
            $table->index(['latitude','longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
