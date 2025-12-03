<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name'=>'PPK RSUD','email'=>'ppk01@test.com','password'=>'ppk123','role'=>'ppk'],
            ['name'=>'Pejabat Pengadaan','email'=>'pejabat01@test.com','password'=>'pejabat123','role'=>'pejabat_pengadaan'],
            ['name'=>'Panitia Pengadaan','email'=>'panitia01@test.com','password'=>'panitia123','role'=>'panitia_pengadaan'],
            ['name'=>'Auditor SPI','email'=>'spi01@test.com','password'=>'spi123','role'=>'auditor'],
        ];

        foreach ($users as $u) {
            User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => $u['role'],
                'password' => Hash::make($u['password']),
            ]);
        }
    }
}

