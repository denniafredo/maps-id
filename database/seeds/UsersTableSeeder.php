<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'SYS', 'email' => 'sys@sys.com', 'password' => Hash::make('sysmapsid'),'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ];

        DB::table('users')->insert($users);
    }
}
