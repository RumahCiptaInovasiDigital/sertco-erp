<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DepartemenSeeder::class,
            RoleSeeder::class,
            MaintenanceSeeder::class,
            EmployeeSeeder::class,
            ServiceSeeder::class,
            KategoriBarangSeeder::class,
            SatuanBarangSeeder::class,
            SuplierSeeder::class,
            VendorSeeder::class,
            JenisKerjaSeeder::class,
            ShiftKerjaSeeder::class,
            UserDeviceSeeder::class,
            JabatanSeeder::class,
            BeritaAcaraHarianSeeder::class,
            BranchOfficeSeeder::class,
            CalendarEventSeeder::class,
            InformationSeeder::class,
            JadwalKerjaSeeder::class,
            JenisKerjaSeeder::class,
            NotifMobileSeeder::class,
            PresensiSeeder::class,
            ResumePresensiSeeder::class,
            PresensiIzinSeeder::class,

        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
