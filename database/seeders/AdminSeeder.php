<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCredential;
use App\Models\UserHasRole;
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
            'nik' => 'SQ-ADM-999',
            'fullname' => 'SuperAdmin',
            'email' => 'admin@mail.com',
            'jabatan' => 'Administrator',
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

        UserHasRole::create([
            'nik' => 'SQ-ADM-999',
            'id_role' => $adminRole->id_role,
        ]);

        UserCredential::create([
            'nik' => 'SQ-ADM-999',
            'pass' => bcrypt('123'),
        ]);
    }
}
