<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_admin()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/user');

        $response->assertStatus(200);
        $response->assertSee('user');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_admin()
    {
        /** @var User $user */
        $user = User::find(4);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/user');

        $response->assertStatus(403);
    }

    public function test_store_user_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/user/store', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['Il campo name è obbligatorio.'],
                    'email' => ['Il campo email è obbligatorio.'],
                    'password' => ['Il campo password è obbligatorio.'],
                    'roles' => ['Il campo roles è obbligatorio.']
                ]
            ]);
    }

    public function test_store_user_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'name' => 'Utente Prova',
            'email' => 'prova@email.it',
            'password' => 'provaprova',
            'confirm_password' => 'provaprova',
            'roles' => ['prova']
        ];

        $this->actingAs($user)->json('POST', 'user/store', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_store_user_errore_ruolo()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'name' => 'Utente Prova 2',
            'email' => 'prova2@email.it',
            'password' => 'provaprova',
            'confirm_password' => 'provaprova',
            'roles' => ['inesistente']
        ];

        $this->actingAs($user)->json('POST', 'user/store', $userData, ['Accept' => 'application/json'])
            ->assertStatus(500);
    }

    public function test_update_user_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/user/update', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['Il campo name è obbligatorio.'],
                    'email' => ['Il campo email è obbligatorio.'],
                    'roles' => ['Il campo roles è obbligatorio.']
                ]
            ]);
    }

    public function test_update_user_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '3',
            'name' => 'lfioretti',
            'email' => 'luca@mail.it',
            'roles' => ['admin']
        ];

        $this->actingAs($user)->json('POST', 'user/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_user_corretto_modifica_psw()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '3',
            'name' => 'lfioretti',
            'email' => 'luca@mail.it',
            'password' => 'lucaluca',
            'confirm_password' => 'lucaluca',
            'roles' => ['admin']
        ];

        $this->actingAs($user)->json('POST', 'user/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_user_errore_ruolo()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '3',
            'name' => 'lfioretti',
            'email' => 'luca@mail.it',
            'password' => 'lucaluca',
            'confirm_password' => 'lucaluca',
            'roles' => ['inesistente']
        ];

        $this->actingAs($user)->json('POST', 'user/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(500);
    }

    public function test_get_user()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/user/' . 3);

        $response->assertStatus(200);
        $response->assertSee('user');
    }
}
