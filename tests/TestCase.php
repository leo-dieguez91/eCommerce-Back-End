<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar Passport solo si no existe el cliente
        if (!DB::table('oauth_clients')->where('id', 1)->exists()) {
            $this->configurePassport();
        }
    }

    protected function configurePassport()
    {
        // Crear cliente de Passport para pruebas
        $clientId = DB::table('oauth_clients')->insertGetId([
            'name' => 'Test Personal Access Client',
            'secret' => Str::random(40),
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear entrada en oauth_personal_access_clients
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $clientId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
