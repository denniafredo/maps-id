<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NewUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['role_id' => 2, 'name' => 'Super Admin', 'email' => 'superadmin@admin.com', 'password' => Hash::make('samapsid'),'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['role_id' => 3, 'name' => 'Admin', 'email' => 'admin@admin.com', 'password' => Hash::make('adminmapsid'),'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ];

        DB::table('users')->insert($users);

        // Update Sys's role_id to 1
        DB::table('users')->where('id', 1)->update(['role_id' => 1]);
    }
}
