<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operators')
        ->insert([
            [
                'name' => 'Super Admin',
                'username' => 'SuperAdmin',
                'role' => "Admin",
                'branch_id' => 1,
                'password' => Hash::make('superadminpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chef',
                'username' => 'Chef',
                'role' => 'chef',
                'branch_id' => 1,
                'password' => Hash::make('chefpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
