<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

        User::create([
            'name' => 'Admin Head Office',
            'email' => 'adminh',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 2,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

        // Create the second user
        User::create([
            'name' => 'Lakmal',
            'email' => 'acc01',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'billing',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

        User::create([
            'name' => 'Iroshi',
            'email' => 'acc02',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'billing',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

        User::create([
            'name' => 'Pro_N',
            'email' => 'pro_n',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'Pro_A',
            'email' => 'pro_a',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'Pro_C',
            'email' => 'pro_c',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'Pro_K',
            'email' => 'pro_k',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

        User::create([
            'name' => 'Pro_J',
            'email' => 'pro_j',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'Pro_R',
            'email' => 'pro_r',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'CT_01',
            'email' => 'ct_01',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'dispatch',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'CT_02',
            'email' => 'ct_02',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'dispatch',
            'branch_id' => 1,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'CT_03',
            'email' => 'ct_03',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'dispatch',
            'branch_id' => 2,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'Pro_L',
            'email' => 'pro_l',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'design',
            'branch_id' => 2,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
        User::create([
            'name' => 'Danoja',
            'email' => 'acc03',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'mode' => 'billing',
            'branch_id' => 2,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);
    }
}


// php artisan make:seeder UserSeeder
// php artisan db:seed --class=UserSeeder
// php artisan db:seed



