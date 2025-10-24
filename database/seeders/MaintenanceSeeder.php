<?php

namespace Database\Seeders;

use App\Models\MaintenanceMode;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaintenanceMode::create([
            'compCode' => 'SQ.01',
            'maintenance' => true,
            'reason' => 'Waiting for Maintenance!',
            'url_hris' => 'https://',
            'idle_time' => 10,
        ]);
    }
}
