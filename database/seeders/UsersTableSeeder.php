<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        //Kullanıcılar tablosuna örnek bir kullanıcı ekleyelim
        User::firstOrCreate([
            'name' => 'Toptancım',
            'surname' => 'Burada',
            'phone' => '1234567890',
            'email' => 'fatihdemir178@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::firstOrCreate([
            'name' => 'Toptancım',
            'surname' => 'User',
            'phone' => '1234567890',
            'email' => 'user@tb.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
