<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class JenisKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_kerja')->insert([
            [
                'nama_jenis_kerja' => 'WFA',
                'keterangan' => 'Work From Anywhere',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jenis_kerja' => 'WFO',
                'keterangan' => 'Work From Office',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
