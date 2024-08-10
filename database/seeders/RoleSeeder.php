<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inputs = [
            [
                'name' => 'Admin',
            ],[
                'name' => 'Vendor',
            ],[
                'name' => 'Customer',
            ]
        ];

        Role::insert($inputs);
    }
}
