<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(['id' => 9, 'role_name'=> 'VOLUNTEER', 'role_description' => 'Tình nguyện viên']);
        DB::table('roles')->insert(['id' => 10, 'role_name'=> 'MEDICAL', 'role_description' => 'Y tế']);
        DB::table('roles')->insert(['id' => 11, 'role_name'=> 'COORDINATOR', 'role_description' => 'Điều phôi']);
    }
}
