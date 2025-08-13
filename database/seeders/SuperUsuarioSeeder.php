<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\PermissionRegistrar;

class SuperUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiamos la caché por si acaso, aunque el otro seeder ya lo hace
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Creamos el usuario Superadmin
        $superadmin = Usuario::firstOrCreate(
            ['email' => 'danielrefa23@gmail.com'],
            [
                'usuario' => 'DanielRF',
                'contrasena_hash' => Hash::make('Admin123'), // ¡Cambia esto!
                'esta_activo' => true,
                'email_verificado' => true,
            ]
        );

        // Le asignamos el rol 'SUPERADMIN' que el otro seeder ya creó.
        // Especificamos el guard 'api' para evitar cualquier ambigüedad.
        $superadmin->assignRole('SUPERADMIN');

        // Creamos el usuario Admin Sucursal
        $adminSuc = Usuario::firstOrCreate(
            ['email' => 'corpusj1493@gmail.com'],
            [
                'usuario' => 'corpus123',
                'contrasena_hash' => Hash::make('Password123'),
                'esta_activo' => true,
                'email_verificado' => true,
            ]
            
        );

        // Le asignamos el rol 'ADMIN_SUC'
        $adminSuc->assignRole('ADMIN_SUC');

                // Creamos el usuario Admin Sucursal
        $adminSuc = Usuario::firstOrCreate(
            ['email' => 'guillermo_escobar128@hotmail.com'],
            [
                'usuario' => 'Escobar',
                'contrasena_hash' => Hash::make('Password123'),
                'esta_activo' => true,
                'email_verificado' => true,
            ]
            
        );

        // Le asignamos el rol 'ADMIN_SUC'
        $adminSuc->assignRole('ADMIN_SUC');

                        // Creamos el usuario Admin Sucursal
        $adminSuc = Usuario::firstOrCreate(
            ['email' => '21170104@uttcampus.edu.mx'],
            [
                'usuario' => 'Charly',
                'contrasena_hash' => Hash::make('Password123'),
                'esta_activo' => true,
                'email_verificado' => true,
            ]
            
        );

        // Le asignamos el rol 'ADMIN_SUC'
        $adminSuc->assignRole('ADMIN_SUC');

    }
}
