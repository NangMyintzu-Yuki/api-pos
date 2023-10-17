<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')
        ->insert([
            [
                'name' => 'User One',
                'username' => 'UserOne',
                'email' => 'nangmyintzu89@gmail.com',
                'phone_no' => "098765432",
                'password' => Hash::make('userpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'User Two',
                'username' => 'UserTwo',
                'email' => 'nangmyintzu89@gmail.com',
                "phone_no" =>"0923456789",
                'password' => Hash::make('userpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ]);
    }
}
