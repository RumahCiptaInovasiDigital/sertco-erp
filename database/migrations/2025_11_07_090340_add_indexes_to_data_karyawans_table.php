<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_karyawans', function (Blueprint $table) {
            $table->index('nik');
            $table->index('fullName');
            $table->index('namaJabatan');
            $table->index('namaDepartemen');
        });
    }

    public function down(): void
    {
        Schema::table('data_karyawans', function (Blueprint $table) {
            $table->dropIndex(['nik']);
            $table->dropIndex(['fullName']);
            $table->dropIndex(['namaJabatan']);
            $table->dropIndex(['namaDepartemen']);
        });
    }
};
