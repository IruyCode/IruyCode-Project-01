<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\BankManager\BankManagerSeeder;
use Database\Seeders\BankManager\BankManagerDebtorSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        $this->call([
            BankManagerSeeder::class,
            BankManagerDebtorSeeder::class
        ]);
    }
}
