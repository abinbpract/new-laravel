<?php

namespace Database\Seeders;

use App\Models\Nominee;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create(
            [
                'username'          => 'mlmadmin',
                'email'             => 'info@cloudlumen.com',
                'password'          => Hash::make('12345678'),
                'is_admin'          => 1,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );
        UserProfile::create([
            'user_id' => $user->id,
        ]);

        Nominee::create([
            'user_id' => $user->id,
        ]);
    }
}
