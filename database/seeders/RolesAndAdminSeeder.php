<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::where('email', 'admin@zendo.pe')->delete();

        User::create([
            'name'               => 'Admin',
            'apellido_paterno'   => 'Zendo',
            'apellido_materno'   => '',
            'celular'            => '999000000',
            'email'              => 'admin@zendo.pe',
            'password'           => bcrypt('admin1234'),
            'role'               => UserRole::Admin,
            'email_verified_at'  => now(),
        ]);

        $this->command->info('Admin creado: admin@zendo.pe / admin1234');
    }
}
