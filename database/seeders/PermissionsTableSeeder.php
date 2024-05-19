<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Db::table('permissions')->truncate();

        $permissions = [
            [
                'name' => 'view-dashboard',
                'description' => 'Kullanıcı oluşturma izni',
            ],
            [
                'name' => 'manage-users',
                'description' => 'Kullanıcı yönetimi izni',
            ],
            [
                'name' => 'edit-settings',
                'description' => 'Ayarları düzenleme izni',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
