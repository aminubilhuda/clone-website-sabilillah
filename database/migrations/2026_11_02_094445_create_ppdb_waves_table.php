<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_waves', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->date('mulai');
            $table->date('selesai');
            $table->unsignedInteger('biaya')->default(0);
            $table->text('benefit')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_waves');
    }
};
