<?php

namespace Database\Seeders;

    use App\Models\Admin\AdminUser;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;

    class UserSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            $defaultPassword = Hash::make('123qweasd');

            $superAdmin = AdminUser::create([
                'pid' => 0,
                'level_id' => 0,
                'channel_id' => 0,
                'name' => 'Super Admin',
                'account' => 'superadmin', // Added account field
                'email' => 'superadmin@wowtvs.com',
                'password' => $defaultPassword,
                'phone' => '',
                'status' => AdminUser::STATUS_ENABLE,
                'is_cancel' => 0,
                'balance' => 0.00,
                'recharge' => 0.00,
                'profit' => 0.00,
                'photo' => '',
                'remark' => '',
                'remember_token' => Str::random(60), // Generate a new token
                'is_new' => 1,
                'is_relation' => 1,
                'type' => 1, // Assuming type 1 for general admin/agents
                'person_num' => 0,
                'try_num' => 0,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // $superAdmin->assignRole(['super admin'], 'admin');

            $diamondAgent = AdminUser::create([
                'pid' => $superAdmin->id, // Assign to super admin
                'level_id' => 1, // Example level ID
                'channel_id' => 0,
                'name' => 'Diamond Agent',
                'account' => 'diamondagent',
                'email' => 'diamond@wowtvs.com',
                'password' => $defaultPassword,
                'phone' => '',
                'status' => AdminUser::STATUS_ENABLE,
                'is_cancel' => 0,
                'balance' => 0.00,
                'recharge' => 0.00,
                'profit' => 0.00,
                'photo' => '',
                'remark' => '',
                'remember_token' => Str::random(60),
                'is_new' => 1,
                'is_relation' => 1,
                'type' => 1,
                'person_num' => 0,
                'try_num' => 0,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $diamondAgent->assignRole(['diamond agent'], 'admin');

            $goldAgent = AdminUser::create([
                'pid' => $diamondAgent->id, // Assign to diamond agent
                'level_id' => 2,
                'channel_id' => 0,
                'name' => 'Gold Agent',
                'account' => 'goldagent',
                'email' => 'gold@wowtvs.com',
                'password' => $defaultPassword,
                'phone' => '',
                'status' => AdminUser::STATUS_ENABLE,
                'is_cancel' => 0,
                'balance' => 0.00,
                'recharge' => 0.00,
                'profit' => 0.00,
                'photo' => '',
                'remark' => '',
                'remember_token' => Str::random(60),
                'is_new' => 1,
                'is_relation' => 1,
                'type' => 1,
                'person_num' => 0,
                'try_num' => 0,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $goldAgent->assignRole(['gold agent'], 'admin');

            $silverAgent = AdminUser::create([
                'pid' => $goldAgent->id, // Assign to gold agent
                'level_id' => 3,
                'channel_id' => 0,
                'name' => 'Silver Agent',
                'account' => 'silveragent',
                'email' => 'silver@wowtvs.com',
                'password' => $defaultPassword,
                'phone' => '',
                'status' => AdminUser::STATUS_ENABLE,
                'is_cancel' => 0,
                'balance' => 0.00,
                'recharge' => 0.00,
                'profit' => 0.00,
                'photo' => '',
                'remark' => '',
                'remember_token' => Str::random(60),
                'is_new' => 1,
                'is_relation' => 1,
                'type' => 1,
                'person_num' => 0,
                'try_num' => 0,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $silverAgent->assignRole(['silver agent'], 'admin');

            $bronzeAgent = AdminUser::create([
                'pid' => $silverAgent->id, // Assign to silver agent
                'level_id' => 4,
                'channel_id' => 0,
                'name' => 'Bronze Agent',
                'account' => 'bronzeagent',
                'email' => 'bronze@wowtvs.com',
                'password' => $defaultPassword,
                'phone' => '',
                'status' => AdminUser::STATUS_ENABLE,
                'is_cancel' => 0,
                'balance' => 0.00,
                'recharge' => 0.00,
                'profit' => 0.00,
                'photo' => '',
                'remark' => '',
                'remember_token' => Str::random(60),
                'is_new' => 1,
                'is_relation' => 1,
                'type' => 1,
                'person_num' => 0,
                'try_num' => 0,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $bronzeAgent->assignRole(['bronze agent'], 'admin');

            $customerAgent = AdminUser::create([
                'pid' => $bronzeAgent->id, // Assign to bronze agent
                'level_id' => 5,
                'channel_id' => 0,
                'name' => 'Custom Agent',
                'account' => 'customagent',
                'email' => 'custom@wowtvs.com',
                'password' => $defaultPassword,
                'phone' => '',
                'status' => AdminUser::STATUS_ENABLE,
                'is_cancel' => 0,
                'balance' => 0.00,
                'recharge' => 0.00,
                'profit' => 0.00,
                'photo' => '',
                'remark' => '',
                'remember_token' => Str::random(60),
                'is_new' => 1,
                'is_relation' => 1,
                'type' => 1,
                'person_num' => 0,
                'try_num' => 0,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $customerAgent->assignRole(['custom agent'], 'admin');

            // hot tv admin user
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
