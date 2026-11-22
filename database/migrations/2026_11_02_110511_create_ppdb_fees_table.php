<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_fees', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('category', 32)->default('BULANAN');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('nominal')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_fees');
    }
};
