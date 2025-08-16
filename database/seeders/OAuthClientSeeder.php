<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OAuthClient;
use Illuminate\Support\Facades\Hash;

class OAuthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OAuthClient::create([
            'name' => 'App Movil de Cliente',
            'client_id' => 'app123',
            'client_secret' => Hash::make('app-secret'),
            'redirect_uri' => 'miapp://callback'
        ]);

         OAuthClient::create([
            'name' => 'App Web de Cliente',
            'client_id' => 'web1',
            'client_secret' => Hash::make('web-secret1'),
            'redirect_uri' => 'https://pagina-prueba.com/web/callback'
        ]);

OAuthClient::create([
            'name' => 'App Web Local',
            'client_id' => 'web-local',
            'client_secret' => Hash::make('web-local-secret'),
            'redirect_uri' => 'http://localhost:3000/oauth/callback'
        ]);

         OAuthClient::create([
            'name' => 'App Web de Cliente',
            'client_id' => 'web123',
            'client_secret' => Hash::make('web-secret'),
            //'redirect_uri' => 'http://localhost:3000/oauth/callback'
            'redirect_uri' => 'https://pagina-prueba.com/api/v1/oauth/callback'
        ]);

    }
}
