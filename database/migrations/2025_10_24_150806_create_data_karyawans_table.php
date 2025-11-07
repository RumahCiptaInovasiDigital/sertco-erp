<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_karyawans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // personal related
            $table->string('fullName');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('pendidikan')->nullable();
            $table->string('tempatLahir')->nullable();
            $table->date('tanggalLahir')->nullable();
            $table->string('noKTP')->nullable();
            $table->string('noSIM')->nullable();
            $table->string('noNPWP')->nullable();
            $table->string('alamat')->nullable();
            $table->string('agama')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('ijazah')->nullable();
            $table->string('foto')->nullable();
            $table->string('statusTK')->nullable();
            $table->string('statusPTKP')->nullable();
            $table->string('noRekening')->nullable();
            // company related
            $table->string('nik')->unique();
            $table->string('inisial');
            $table->string('grade')->nullable();
            $table->string('nppBpjsTk')->nullable();
            $table->string('BpjsKes')->nullable();
            $table->string('AXA')->nullable();
            $table->uuid('idJabatan');
            $table->string('namaJabatan');
            $table->uuid('idDepartemen')->nullable();
            $table->string('namaDepartemen')->nullable();
            $table->date('empDateStart');
            $table->date('empDateEnd')->nullable();
            $table->date('joinDate');
            $table->date('resignDate')->nullable();
            // emergency contact
            $table->string('emergencyContact')->nullable();
            $table->string('emergencyName')->nullable();
            $table->string('emergencyRelation')->nullable();
            // dbinfo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_karyawans');
    }
};
