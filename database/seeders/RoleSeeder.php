<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'super admin']);
        Role::create(['name' => 'diamond agent']);
        Role::create(['name' => 'gold agent']);
        Role::create(['name' => 'silver agent']);
        Role::create(['name' => 'bronze agent']);
        Role::create(['name' => 'custom agent']);
    }
}
