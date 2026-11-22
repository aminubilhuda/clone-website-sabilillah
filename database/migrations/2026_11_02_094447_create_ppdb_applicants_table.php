<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wave_id')->nullable()->constrained('ppdb_waves')->nullOnDelete();
            $table->string('name');
            $table->string('nisn', 20)->nullable();
            $table->string('asal_sekolah');
            $table->string('jurusan', 32)->nullable();
            $table->string('jurusan_2', 32)->nullable();
            $table->string('parent_name');
            $table->string('whatsapp', 32);
            $table->text('alamat');
            $table->string('status', 16)->default('baru')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_applicants');
    }
};
