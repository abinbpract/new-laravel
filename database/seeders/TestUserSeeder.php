<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Nominee;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            // Create user
            $user = User::create([
                'username' => $faker->userName,
                'email'    => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'is_admin' => $faker->boolean(20),           // 20% chance of being an admin
            ]);

            // Create user profile
            UserProfile::create([
                'user_id'               => $user->id,
                'first_name'            => $faker->firstName,
                'last_name'             => $faker->lastName,
                'address_line1'         => $faker->streetAddress,
                'address_line2'         => $faker->secondaryAddress,
                'city'                  => $faker->city,
                'state'                 => $faker->state,
                'country'               => $faker->country,
                'country_code'          => '+1',
                'mobile_number'         => $faker->phoneNumber,
                'alternative_number'    => $faker->phoneNumber,
                'national_id_number'    => $faker->unique()->numerify('ID######'),
                'national_id_type'      => $faker->randomElement(['Passport', 'Driver License', 'National ID']),
                'national_id_copy_path' => $faker->imageUrl(640, 480, 'people'),
                'date_of_birth'         => $faker->date('Y-m-d', '2000-01-01'),
            ]);

            // Create nominee
            Nominee::create([
                'user_id'        => $user->id,
                'email'          => $faker->unique()->safeEmail,
                'nominee_name'   => $faker->name,
                'relationship'   => $faker->randomElement(['Spouse', 'Sibling', 'Parent', 'Friend']),
                'contact_number' => $faker->phoneNumber,
            ]);
        }
    }
}
