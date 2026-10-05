<?php

namespace Database\Factories\MasterData;

use App\Models\MasterData\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Member>
 */
#[UseModel(Member::class)]
class MemberFactory extends Factory
{
    protected $nextNumber = 0;

    protected function getNumber()
    {
        if($this->nextNumber > 0) {
            return str_pad($this->nextNumber++, 5, "0", STR_PAD_LEFT);
        }

        $this->nextNumber = DB::table('members')->count() + 1;

        return $this->getNumber();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = rand(0, 1) == 1 ? 'male' : 'female';
        $birthdate = $this->faker->date('dmy');
        $nik = $this->faker->numberBetween(3,7);
        $nik .= $this->faker->numberBetween(0,9);
        for($i = 0; $i < 2; $i++) {
            $nik .= $this->faker->numberBetween(1, 9);
            $nik .= $this->faker->numberBetween(0, 9);
        }
        $nik .= $birthdate . str_pad($this->faker->randomNumber(4), 4, "0");

        return [
            'user_id' => User::factory(),
            'member_code' => 'ID' . $this->getNumber(),
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName($gender),
            'gender' => strtoupper(substr($gender, 0, 1)),
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'nik' => $nik,
            'plate_number' => 'D' . $this->faker->buildingNumber() . 'AC',
            'phone_number' => $this->faker->phoneNumber(),
            'emergency_person' => $this->faker->name,
            'emergency_phone_number' => $this->faker->phoneNumber()
        ];
    }
}
