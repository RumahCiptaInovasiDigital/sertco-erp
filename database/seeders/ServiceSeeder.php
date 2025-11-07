<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['1' => 'Pressure Safety Valve'],
            ['2' => 'Pressure Vessel'],
            ['3' => 'Compressor'],
            ['4' => 'Pump'],
            ['5' => 'Generator'],
            ['6' => 'Panel Distribution (MCC/Switchgear/Trans)'],
            ['7' => 'Crane'],
            ['8' => 'Storage Tank'],
            ['9' => 'Breather Valve'],
            ['10' => 'Platform'],
            ['11' => 'Metering System'],
            ['12' => 'Pipeline Installation (PLO)'],
            ['13' => 'Plant Installation (PLO)'],
            ['14' => 'RIG Installation (PLO)'],
            ['15' => 'WPS/PQR'],
            ['16' => 'Welder'],
            ['17' => 'Valve'],
            ['18' => 'Man Power Supply'],
        ];

        foreach ($services as $key => $value) {
            \DB::table('kategori_services')->insert([
                'id_kategori_service' => \Str::uuid(),
                'name' => $value[$key + 1],
                'sort_num' => $key + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $serviceTypes = [
            ['1' => 'Migas Certification'],
            ['2' => 'NDT'],
            ['3' => 'WPS/PQR'],
            ['4' => 'Engineering Assessment'],
            ['5' => 'Technical Advisory'],
            ['6' => 'Other (Describe)'],
        ];

        foreach ($serviceTypes as $key => $value) {
            \DB::table('service_types')->insert([
                'id_service_type' => \Str::uuid(),
                'name' => $value[$key + 1],
                'sort_num' => $key + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
