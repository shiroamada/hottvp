<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PreGeneratedCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = [
                'code' => Str::random(10),
                'imported_by' => 1,
                'imported_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pre_generated_codes')->insert($codes);
    }
}