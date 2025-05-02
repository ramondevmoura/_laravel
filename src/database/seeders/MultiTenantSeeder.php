<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class MultiTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@plataforma.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $partner = User::create([
            'name' => 'Empresa X',
            'email' => 'parceiro@empresa.com',
            'password' => Hash::make('partner123'),
            'role' => 'partner'
        ]);

        $tenant = Tenant::create([
            'name' => 'Empresa X',
            'slug' => 'empresa-x',
            'owner_id' => $partner->id
        ]);

        $partner->tenant_id = $tenant->id;
        $partner->save();

        User::create([
            'name' => 'Funcionario 1',
            'email' => 'funcionario1@empresa.com',
            'password' => Hash::make('func123456'),
            'role' => 'employee',
            'tenant_id' => $tenant->id
        ]);
    }
}
