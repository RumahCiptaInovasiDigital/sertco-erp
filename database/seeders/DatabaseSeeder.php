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
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
