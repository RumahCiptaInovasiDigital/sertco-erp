<?php

namespace Database\Seeders;

use App\Models\UserCredential;
use App\Models\UserDevice;
use Illuminate\Database\Seeder;

class UserDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $niks = UserCredential::pluck('nik')->toArray();
        foreach ($niks as $nik) {

            UserDevice::factory()->create([
                'user_credential_nik' => $nik,
            ]);

        }


    }
}
