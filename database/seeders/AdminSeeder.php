<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menentukan role Administrator
        $adminRole = Role::firstOrCreate([
            'name' => 'Administrator',
            // 'slug_name' => 'administrator',
        ]);
        // dd($adminRole);

        // Menambahkan user Administrator
        User::create([
            'NIK' => '000000000',
            'fullname' => 'SuperAdmin',
            'email' => 'admin@mail.com',
            'jobLvl' => 'Administrator',
            'password' => \Hash::make('123'),
        ]);

        $routes = \Route::getRoutes()->getRoutesByName();

        foreach ($routes as $routeName => $route) {
            // Simpan routeName dan URL ke tabel permissions
            Permission::create([
                'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                'role_id' => $adminRole->id_role, // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }
    }
}
