<?php

namespace App\Console\Commands;

use App\Models\MasterNotifikasi;
use App\Services\SendNotifToEmployee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendScheduleNotification extends Command
{
    protected $signature = 'notifications:send-scheduled';
    protected $description = 'Kirim notifikasi otomatis berdasarkan jadwal (daily, weekly, monthly, yearly)';

    public function handle()
    {
        $now = Carbon::now();

        $this->info('Memulai pengecekan notifikasi otomatis...');

        // Ambil semua notifikasi aktif
        $notifikasis = MasterNotifikasi::query()
            ->where('is_active', true)
            ->get();

        foreach ($notifikasis as $notif) {
            $kirim = false;

            switch ($notif->jenis_notifikasi) {
                case 'daily':
                    $kirim = $now->format('H:i') === Carbon::parse($notif->jam_notifikasi)->format('H:i');
                    break;

                case 'weekly':
                    $kirim = strtolower($now->format('l')) === strtolower($notif->hari_notifikasi);
                    break;

                case 'monthly':
                    $kirim = $now->day == $notif->tanggal_notifikasi;
                    break;

                case 'yearly':
                    $kirim = $now->day == $notif->tanggal_notifikasi && $now->month == $notif->bulan_notifikasi;
                    break;

                case 'sekali':
                    // Sekali biasanya dikirim manual via tombol
                    continue 2;
            }

            if ($kirim) {
                $this->sendNotification($notif);
            }
        }

        $this->info('Cron job notifikasi selesai dijalankan.');
    }

    private function sendNotification($notif)
    {
        $karyawanList = $notif->usersNotifikasi;

        (new SendNotifToEmployee())->handle($karyawanList, $notif, true,null);
        // $unsent = $notif->karyawans()
        //     ->get();

        // foreach ($unsent as $karyawan) {
        //     (new SendNotifToEmployee())->handle($karyawan, $notif, null);

        //     $this->line("Notifikasi '{$notif->title}' dikirim ke: {$karyawan->fullName}");
        // }
    }
}
