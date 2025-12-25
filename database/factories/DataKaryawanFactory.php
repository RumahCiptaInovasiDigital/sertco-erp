<?php

namespace Database\Factories;

use App\Models\DataKaryawan;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataKaryawanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DataKaryawan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $role = Role::factory()->create();
        $departemen = Departemen::factory()->create();
        return [
            'fullName' => $firstName . ' ' . $lastName,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'pendidikan' => 'S1',
            'tempatLahir' => $this->faker->city,
            'tanggalLahir' => $this->faker->date(),
            'noKTP' => $this->faker->numerify('################'),
            'noSIM' => $this->faker->numerify('##############'),
            'noNPWP' => $this->faker->numerify('################'),
            'alamat' => $this->faker->address,
            'agama' => 'Islam',
            'email' => $this->faker->unique()->safeEmail,
            'phoneNumber' => $this->faker->phoneNumber,
            'statusTK' => 'TK/0',
            'statusPTKP' => 'TK/0',
            'noRekening' => $this->faker->bankAccountNumber,
            'nik' => $this->faker->unique()->numerify('SQ-LEN-###'),
            'inisial' => 'TES',
            'grade' => '1',
            'nppBpjsTk' => $this->faker->numerify('##########'),
            'BpjsKes' => $this->faker->numerify('##########'),
            'AXA' => $this->faker->numerify('##########'),
            'idJabatan' => $role->id_role,
            'namaJabatan' => $role->name,
            'idDepartemen' => $departemen->id_departemen,
            'namaDepartemen' => $departemen->name,
            'empDateStart' => $this->faker->date(),
            'joinDate' => $this->faker->date(),
            'emergencyContact' => $this->faker->phoneNumber,
            'emergencyName' => $this->faker->name,
            'emergencyRelation' => 'Saudara',
        ];
    }
}
