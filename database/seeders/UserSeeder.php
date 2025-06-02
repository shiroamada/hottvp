<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('Hot@2025');

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@hottvplus.com',
            'password' => $defaultPassword,
            'is_admin' => true, // Assuming super admin also has the old is_admin flag
        ]);
        $superAdmin->assignRole('super admin');

        $diamondAgent = User::create([
            'name' => 'Diamond Agent',
            'email' => 'diamond@hottvplus.com',
            'password' => $defaultPassword,
        ]);
        $diamondAgent->assignRole('diamond agent');

        $goldAgent = User::create([
            'name' => 'Gold Agent',
            'email' => 'gold@hottvplus.com',
            'password' => $defaultPassword,
        ]);
        $goldAgent->assignRole('gold agent');

        $silverAgent = User::create([
            'name' => 'Silver Agent',
            'email' => 'silver@hottvplus.com',
            'password' => $defaultPassword,
        ]);
        $silverAgent->assignRole('silver agent');

        $bronzeAgent = User::create([
            'name' => 'Bronze Agent',
            'email' => 'bronze@hottvplus.com',
            'password' => $defaultPassword,
        ]);
        $bronzeAgent->assignRole('bronze agent');

        $customerAgent = User::create([
            'name' => 'Custom Agent',
            'email' => 'custom@hottvplus.com',
            'password' => $defaultPassword,
        ]);
        $customerAgent->assignRole('custom agent');
    }
}
