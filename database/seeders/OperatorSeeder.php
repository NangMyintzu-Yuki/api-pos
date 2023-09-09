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
                'role' => 1,
                'branch_id' => 1,
                'password' => Hash::make('superadminpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Admin',
                'username' => 'Admin',
                'role' => 2,
                'branch_id' => 1,
                'password' => Hash::make('adminpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chef',
                'username' => 'Chef',
                'role' => 3,
                'branch_id' => 1,
                'password' => Hash::make('chefpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Waiter',
                'username' => 'Waiter',
                'role' => 4,
                'branch_id' => 1,
                'password' => Hash::make('waiterpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Delivery Man',
                'username' => 'Delivery Man',
                'role' => 5,
                'branch_id' => 1,
                'password' => Hash::make('deliverypassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chaiser',
                'username' => 'Chaiser',
                'role' => 6,
                'branch_id' => 1,
                'password' => Hash::make('chaiserpassword'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
