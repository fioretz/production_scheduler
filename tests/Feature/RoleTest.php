<?php

namespace Tests\Feature;

use App\Http\Controllers\RoleController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class RoleTest extends TestCase
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
            ->get('/role');

        $response->assertStatus(200);
        $response->assertSee('role');
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
            ->get('/role');

        $response->assertStatus(403);
    }

    public function test_store_role_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/role/store', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['Il campo name è obbligatorio.']
                ]
            ]);
    }

    public function test_store_role_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $roleData = [
            'name' => 'Ruolo di Test',
        ];

        $this->actingAs($user)->json('POST', 'role/store', $roleData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_role_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/role/update', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['Il campo name è obbligatorio.'],
                ]
            ]);
    }

    public function test_update_role_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '10',
            'name' => 'prova',
        ];

        $this->actingAs($user)->json('POST', 'role/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_role_errore()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', 'role/' . 2 . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_role_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $role = new Role();
        $role->name = 'ruolo test 2';
        $role->save();

        $role = Role::where('name', '=', 'ruolo test 2')->first();
        $id = $role->id;


        $this->actingAs($user)->json('POST', 'role/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_get_user()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/role/' . 2);

        $response->assertStatus(200);
        $response->assertSee('role');
    }
}
