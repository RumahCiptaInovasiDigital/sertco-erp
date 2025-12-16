<?php

namespace Database\Factories;

use App\Models\Enum\StatusDevice;
use App\Models\UserCredential;
use App\Models\UserDevice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserDeviceFactory extends Factory
{
    protected $model = UserDevice::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement( StatusDevice::values() );
        $phone = $this->faker->randomElement( $this->phones() );
        return [
            'id' => $this->faker->uuid(),
            'user_credential_nik' => UserCredential::inRandomOrder()->first()->nik,
            'device_id' => $this->faker->unique()->uuid,
            'device_name' => $phone['device_name'],
            'device_type' => $phone['device_type'],
            'fcm_token' => Str::random(152),
            'ip_address' => $this->faker->ipv4(),
            'coordinate' => json_encode([
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude,
            ]),
            'status' => $status,
            'history' => json_encode([]),
            'register_new' => json_encode([]),
            'activate_at' => $this->faker->dateTimeThisYear(),
            'blocked_at' => $status === 'blocked' ? $this->faker->dateTimeThisYear() : null,
            'reason_blocked' => $status === 'blocked' ? $this->faker->sentence() : null,
            'news' => null,
            'validator_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function phones(){
        return [
            [
                'device_name' => 'Samsung Galaxy S23',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'iPhone 15 Pro',
                'device_type' => 'ios',
            ],
            [
                'device_name' => 'Google Pixel 8',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Xiaomi 13T',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'OnePlus 11',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'iPhone SE (2024)',
                'device_type' => 'ios',
            ],
            [
                'device_name' => 'Infinix Zero 5G 2023',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Motorola Edge 40',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Sony Xperia 1 V',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Techno Phantom X2',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Iphone 14',
                'device_type' => 'ios',
            ],
            [
                'device_name' => 'Samsung Galaxy A54',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Oppo Reno8',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Vivo V27',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'Realme 11 Pro',
                'device_type' => 'android',
            ],
            [
                'device_name' => 'IPhone 13',
                'device_type' => 'ios',
            ]
        ];
    }
}
