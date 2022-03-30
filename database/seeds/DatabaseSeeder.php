<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        DB::table('user_approval_statuses')->insert([
            ['status' => 'Pending'],
            ['status' => 'Active'],
            ['status' => 'Suspended'],
        ]);
        DB::table('user_roles')->insert([
            ['name' => 'Supper Admin'],
            ['name' => 'Admin'],
            ['name' => 'Manager']
        ]);
        DB::table('payment_methods')->insert([
            ['method' => 'Cash'],
            ['method' => 'Bkash'],
            ['method' => 'Roket'],
            ['method' => 'Nogod'],
            ['method' => 'Bank']
        ]);
    }
}
