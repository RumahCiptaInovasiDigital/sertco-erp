<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKaryawan;
use App\Models\PresensiIzin;
use Carbon\Carbon;

class PresensiIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some employees to create izin for
        $karyawans = DataKaryawan::inRandomOrder()->limit(5)->get();

        if ($karyawans->isEmpty()) {
            $this->command->info('Tidak ada data karyawan ditemukan, seeding PresensiIzin dibatalkan.');
            return;
        }

        $jenis_izin_options = ['sakit', 'cuti', 'izin', 'tugas'];
        $status_options = ['pengajuan', 'disetujui', 'ditolak'];

        foreach ($karyawans as $karyawan) {
            $tanggal_mulai = Carbon::now()->subDays(rand(1, 30));
            $tanggal_selesai = $tanggal_mulai->copy()->addDays(rand(0, 3));
            $jenis_izin = $jenis_izin_options[array_rand($jenis_izin_options)];
            $status = $status_options[array_rand($status_options)];

            PresensiIzin::create([
                'karyawan_id' => $karyawan->id,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'jenis_izin' => $jenis_izin,
                'keterangan' => 'Keterangan untuk izin ' . $jenis_izin,
                'status' => $status,
                'approved_by' => ($status != 'pengajuan') ? DataKaryawan::inRandomOrder()->first()->id : null,
                'approved_at' => ($status != 'pengajuan') ? Carbon::now() : null,
                'catatan_approver' => ($status != 'pengajuan') ? 'Catatan dari approver.' : null,
            ]);
        }
    }
}
