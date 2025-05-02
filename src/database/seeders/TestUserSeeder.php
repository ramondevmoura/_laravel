<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'teste@example.com'],
            [
                'name' => 'Usuário Teste',
                'email' => 'teste@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );
    }
}
