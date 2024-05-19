<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_permissions')->truncate();

        /**
         * user_id 1 => Admin 2 => User
         * Admin kullanıcısına tüm yetkileri verelim
         * User kullancısına sadece view-dashboard yetkisini verelim
         */
        DB::table('user_permissions')->insert([
            [
                'user_id' => 1,
                'permission_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'permission_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'permission_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'permission_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }
}
