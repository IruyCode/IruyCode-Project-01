<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin007 IruyCode',
                'email' => 'admin@iruy.test',
                'password' => bcrypt('password'), // ou hash correto
                'type_user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'cliente Obra',
                'email' => 'cliente@iruy.test',
                'password' => bcrypt('password'),
                'type_user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('type_users')->insert([['type_user' => 'admin'], ['type_user' => 'cliente']]);
    }
}
