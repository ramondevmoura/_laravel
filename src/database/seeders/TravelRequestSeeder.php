<?php

namespace Database\Seeders;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class TravelRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuários fixos se não existirem
        $users = collect([
            ['name' => 'João Almeida', 'email' => 'joao.almeida@example.com'],
            ['name' => 'Maria Castro', 'email' => 'maria.castro@example.com'],
            ['name' => 'Lucas Fernandes', 'email' => 'lucas.fernandes@example.com'],
            ['name' => 'Ana Beatriz', 'email' => 'ana.beatriz@example.com'],
        ])->map(function ($userData) {
            return User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'), // senha padrão
                ]
            );
        });

        $destinos = ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Fortaleza', 'Porto Alegre', 'Brasília', 'Salvador', 'Recife', 'Manaus'];
        $status = ['solicitado', 'aprovado', 'cancelado'];

        // Criar 20 pedidos aleatórios distribuídos entre os usuários
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random(); // pega um usuário aleatório

            $departure = Carbon::now()->addDays(rand(1, 60));
            $return = (clone $departure)->addDays(rand(1, 10));

            TravelRequest::create([
                'user_id' => $user->id,
                'destination' => $destinos[array_rand($destinos)],
                'departure_date' => $departure->toDateString(),
                'return_date' => $return->toDateString(),
                'status' => $status[array_rand($status)],
            ]);
        }
    }
}
