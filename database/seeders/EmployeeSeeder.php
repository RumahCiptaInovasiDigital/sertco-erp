<?php

namespace Database\Seeders;

use App\Models\UserCredential;
use App\Models\UserHasRole;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ambil semua role dari tabel roles
        $roles = DB::table('roles')->get();

        for ($i = 1; $i <= 50; ++$i) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $fullName = "$firstName $lastName";

            // inisial 3 huruf (2 dari depan + 1 dari belakang)
            $inisial = strtoupper(substr($firstName, 0, 2).substr($lastName, 0, 1));
            $inisial = str_pad(substr($inisial, 0, 3), 3, 'X');

            // format NIK: SQ-(inisial3huruf)-(no urut 3 digit)
            $nik = sprintf('SQ-%s-%03d', $inisial, $i);

            // ambil role random dari tabel roles
            $role = $roles->random();

            // tanggal acak manual (tanpa dateTimeBetween)
            $yearStart = $faker->numberBetween(2018, 2023);
            $monthStart = str_pad($faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
            $dayStart = str_pad($faker->numberBetween(1, 28), 2, '0', STR_PAD_LEFT);

            $yearEnd = $yearStart + $faker->numberBetween(0, 2);
            $monthEnd = str_pad($faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
            $dayEnd = str_pad($faker->numberBetween(1, 28), 2, '0', STR_PAD_LEFT);

            DB::table('data_karyawans')->insert([
                'id' => Str::uuid(),
                'fullName' => $fullName,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'pendidikan' => $faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
                'tempatLahir' => $faker->city,
                'tanggalLahir' => $faker->date('Y-m-d', '2000-12-31'),
                'noKTP' => $faker->numerify('################'),
                'noSIM' => $faker->optional()->numerify('##########'),
                'noNPWP' => $faker->optional()->numerify('##.###.###.#-###.###'),
                'alamat' => $faker->address,
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Buddha', 'Konghucu']),
                'email' => $faker->unique()->safeEmail,
                'phoneNumber' => $faker->phoneNumber,
                'ijazah' => $faker->optional()->word().'.pdf',
                'foto' => $faker->optional()->imageUrl(300, 300, 'people', true, 'Foto'),
                'statusTK' => $faker->randomElement(['PKWT', 'PKWTT', 'FreeLance']),
                'statusPTKP' => $faker->randomElement(['PTKP', 'Non PTKP']),
                'noRekening' => $faker->bankAccountNumber,

                // company related
                'nik' => $nik,
                'inisial' => $inisial,
                'grade' => $faker->optional()->randomElement(['I', 'II', 'III', 'IV', 'IVA', 'IVB', 'V', 'VI', 'VII', 'VIII']),
                'nppBpjsTk' => $faker->optional()->numerify('#########'),
                'BpjsKes' => $faker->randomElement(['YA', 'TIDAK']),
                'AXA' => $faker->randomElement(['YA', 'TIDAK']),
                'idJabatan' => $role->id_role,
                'namaJabatan' => $role->name,
                'idDepartemen' => null,
                'namaDepartemen' => null,
                'empDateStart' => "$yearStart-$monthStart-$dayStart",
                'empDateEnd' => "$yearEnd-$monthEnd-$dayEnd",
                'joinDate' => "$yearStart-$monthStart-$dayStart",
                'resignDate' => $faker->optional()->date('Y-m-d'),

                // emergency contact
                'emergencyContact' => $faker->phoneNumber,
                'emergencyName' => $faker->name,
                'emergencyRelation' => $faker->randomElement(['Istri', 'Suami', 'Orang Tua', 'Saudara', 'Teman']),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            UserHasRole::create([
                'nik' => $nik,
                'id_role' => $role->id_role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            UserCredential::create([
                'nik' => $nik,
                'pass' => password_hash('password123', PASSWORD_DEFAULT),
            ]);
        }
    }
}
