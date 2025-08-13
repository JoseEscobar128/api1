<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('puestos')->insert([
            [
                'id' => 1,
                'nombre' => 'GERENTE',
                'descripcion' => 'Responsable de la administración general del negocio.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'CAJERO',
                'descripcion' => 'Encargado de manejar las transacciones y cobros.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'MESERO',
                'descripcion' => 'Atiende a los clientes y toma órdenes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'COCINERO',
                'descripcion' => 'Prepara los alimentos según el menú.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
