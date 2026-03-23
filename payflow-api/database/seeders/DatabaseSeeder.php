<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $seederNames = [
            UserSeeder::class,
        ];

        foreach ($seederNames as $seederName) {
            $exists = DB::table('seeders')
                ->where('name', $seederName)
                ->exists();

            if (! $exists) {
                $this->call($seederName);
                DB::table('seeders')->insert([
                    'name' => $seederName,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
