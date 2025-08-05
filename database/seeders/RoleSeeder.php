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
        Role::create(['name' => 'super admin', 'guard_name' => 'admin']);
        Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        Role::create(['name' => 'diamond agent', 'guard_name' => 'admin']);
        Role::create(['name' => 'gold agent', 'guard_name' => 'admin']);
        Role::create(['name' => 'silver agent', 'guard_name' => 'admin']);
        Role::create(['name' => 'bronze agent', 'guard_name' => 'admin']);
        Role::create(['name' => 'custom agent', 'guard_name' => 'admin']);
    }
}
