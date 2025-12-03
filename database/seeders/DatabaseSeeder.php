<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Unit;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            $this->call([
        UserRoleSeeder::class,
    ]);

        // 🌱 1. Jalankan seeder unit dulu
        $this->call(UnitSeeder::class);

        // 🌱 2. Buat user default untuk testing
        $farmasi = Unit::where('name', 'Instalasi Farmasi')->first();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // default password
            'role' => 'unit', // misal default role-nya unit
            'unit_id' => $farmasi ? $farmasi->id : null,
        ]);
    }
}
