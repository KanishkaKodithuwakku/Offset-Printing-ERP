<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'branch_name' => 'Kiribathgoda',
            'branch_code' => 'MGK',
            'location' => 'Kiribathgoda',
            'contact_number' => '0123656655',
            'description' => 'Kiribathgoda'
        ]);
        Branch::create([
            'branch_name' => 'Maharagama',
            'branch_code' => 'MGM',
            'location' => 'Maharagama',
            'contact_number' => '0123654477',
            'description' => 'Maharagama'
        ]);
    }
}
