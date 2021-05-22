<?php

namespace Tests\Feature;

use App\Models\Macchina;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MacchinaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_logged()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/macchina');

        $response->assertStatus(200);
        $response->assertSee('macchina');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/macchina');

        $response->assertStatus(302);
    }

    public function test_store_macchina_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/macchina/store', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'codice' => ['Il campo codice è obbligatorio.'],
                    'descrizione' => ['Il campo descrizione è obbligatorio.'],
                    'tipomacchina_id' => ['Il campo tipomacchina id è obbligatorio.'],
                ]
            ]);
    }

    public function test_store_macchina_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $macchinaData = [
            'codice' => 'MC TEST',
            'descrizione' => 'MACCHINA TEST',
            'tipomacchina_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'macchina/store', $macchinaData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_macchina_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/macchina/update', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'codice' => ['Il campo codice è obbligatorio.'],
                    'descrizione' => ['Il campo descrizione è obbligatorio.'],
                    'tipomacchina_id' => ['Il campo tipomacchina id è obbligatorio.']
                ]
            ]);
    }

    public function test_update_macchina_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '1',
            'codice' => 'HUF0',
            'descrizione' => 'HUFIMAT 0',
            'tipomacchina_id' => '2',
        ];

        $this->actingAs($user)->json('POST', 'macchina/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_macchina_errore()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '90',
            'codice' => 'HUF0',
            'descrizione' => 'HUFIMAT 0',
            'tipomacchina_id' => '2',
        ];

        $this->actingAs($user)->json('POST', 'macchina/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(500);
    }

    public function test_delete_macchina_errore()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', 'macchina/' . 90 . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(500);
    }

    public function test_delete_macchina_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $role = Macchina::where('codice', '=', 'MC TEST')->first();
        $id = $role->id;

        $this->actingAs($user)->json('POST', 'macchina/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_get_macchina()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/macchina/' . 1);

        $response->assertStatus(200);
        $response->assertSee('macchina');
    }
}
