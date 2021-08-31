<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['role' => 'System'],
            ['role' => 'Super Admin'],
            ['role' => 'Admin'],
        ];

        DB::table('roles')->insert($roles);
    }
}
