<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
        });

        // Isi username akun bawaan agar bisa langsung dipakai.
        DB::table('users')->where('email', 'admin@smkabdinegara.sch.id')->update(['username' => 'admin']);
        DB::table('users')->where('email', 'editor@smkabdinegara.sch.id')->update(['username' => 'editor']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
