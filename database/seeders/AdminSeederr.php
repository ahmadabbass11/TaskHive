<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeederr extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin2',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password123'), 
            'role' => 'admin',
        ]);
    }
}
