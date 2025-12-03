<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Instalasi Farmasi', 'kepala_unit' => 'dr. Andi', 'email_unit' => 'farmasi@rsudbangil.go.id'],
            ['name' => 'Instalasi Gizi', 'kepala_unit' => 'dr. Sinta', 'email_unit' => 'gizi@rsudbangil.go.id'],
            ['name' => 'Instalasi IT', 'kepala_unit' => 'Pak Dimas', 'email_unit' => 'it@rsudbangil.go.id'],
            ['name' => 'Instalasi Umum', 'kepala_unit' => 'Bu Nani', 'email_unit' => 'umum@rsudbangil.go.id'],
            ['name' => 'Instalasi Radiologi', 'kepala_unit' => 'dr. Dewa', 'email_unit' => 'radiologi@rsudbangil.go.id'],
        ];

        foreach ($units as $unit) {
            // Buat unit jika belum ada
            $createdUnit = Unit::firstOrCreate(['name' => $unit['name']], $unit);

            // Buat akun user untuk setiap unit (role = 'unit')
            User::firstOrCreate(
                ['email' => $unit['email_unit']],
                [
                    'name' => $unit['kepala_unit'],
                    'password' => Hash::make('12345678'), // 🔐 password default
                    'role' => 'unit',
                    'unit_id' => $createdUnit->id,
                ]
            );
        }
    }
}
