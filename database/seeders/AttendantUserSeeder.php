<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AttendantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Atendente 01',
            'email' => 'atendente01@avantar.com',
            'password' => Hash::make('password'),
            'role' => 'atendente',
        ]);

        DB::table('users')->insert([
            'name' => 'Atendente 02',
            'email' => 'atendente02@avantar.com',
            'password' => Hash::make('password'),
            'role' => 'atendente',
        ]);
    }
}
