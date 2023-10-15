<?php

namespace Database\Seeders;

use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $table = [];
                $table[] = [
                    'branch_id' => 1,
                    "table_no" => $i,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

            DB::table('tables')->insert($table);
        }

    }
}
