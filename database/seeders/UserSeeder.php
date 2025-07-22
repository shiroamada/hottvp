<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin\AdminUser;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('123qweasd');

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@wowtvs.com',
            'password' => $defaultPassword,
            'is_admin' => true, // Assuming super admin also has the old is_admin flag
        ]);
        $superAdmin->assignRole('super admin');

        $diamondAgent = User::create([
            'name' => 'Diamond Agent',
            'email' => 'diamond@wowtvs.com',
            'password' => $defaultPassword,
        ]);
        $diamondAgent->assignRole('diamond agent');

        $goldAgent = User::create([
            'name' => 'Gold Agent',
            'email' => 'gold@wowtvs.com',
            'password' => $defaultPassword,
        ]);
        $goldAgent->assignRole('gold agent');

        $silverAgent = User::create([
            'name' => 'Silver Agent',
            'email' => 'silver@wowtvs.com',
            'password' => $defaultPassword,
        ]);
        $silverAgent->assignRole('silver agent');

        $bronzeAgent = User::create([
            'name' => 'Bronze Agent',
            'email' => 'bronze@wowtvs.com',
            'password' => $defaultPassword,
        ]);
        $bronzeAgent->assignRole('bronze agent');

        $customerAgent = User::create([
            'name' => 'Custom Agent',
            'email' => 'custom@wowtvs.com',
            'password' => $defaultPassword,
        ]);
        $customerAgent->assignRole('custom agent');

        //hot tv admin user
        $hotTvAdmin = AdminUser::create([
            'pid' => 0,
            'level_id' => 0,
            'channel_id' => 0,
            'name' => 'Hot TV Admin',
            'account' => 'storeadmin123',
            'email' => 'superadmin@wowtvs.com',
            'password' => $defaultPassword,
            'phone' => '',
            'status' => AdminUser::STATUS_ENABLE,
            'is_cancel' => 0,
            'balance' => 395000.00,
            'recharge' => 0.00,
            'profit' => 0.00,
            'photo' => '',
            'remark' => '',
            'remember_token' => '9gLXw4zFs50FDleKTMinQC0kr4uNWoaH0Ox5JoPFRxAXnZNy2Ogwfm4nxdSS',
            'is_new' => 1,
            'is_relation' => 1,
            'type' => 2, // Assuming type 2 is for hot tv admin
            'person_num' => 0,
            'try_num' => 0,
            'language' => 'zh',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
