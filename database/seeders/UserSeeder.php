<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Administrador',
            'email' => 'superadmin@geoconecta.com',
            'password' => Hash::make('tgro$2Mm8^Th38bC$pKf8E')
        ]);

       


    }
}
