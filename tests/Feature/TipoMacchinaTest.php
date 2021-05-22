<?php

namespace Tests\Feature;

use App\Models\TipoMacchina;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TipoMacchinaTest extends TestCase
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
            ->get('/tipomacchina');

        $response->assertStatus(200);
        $response->assertSee('tipomacchina');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/tipomacchina');

        $response->assertStatus(302);
    }

    public function test_store_tipomacchina_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/tipomacchina/store', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'codice' => ['Il campo codice è obbligatorio.'],
                    'descrizione' => ['Il campo descrizione è obbligatorio.'],
                ]
            ]);
    }

    public function test_store_tipomacchina_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'codice' => 'TIPO TEST',
            'descrizione' => 'TIPO TEST',
        ];

        $this->actingAs($user)->json('POST', 'tipomacchina/store', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_tipomacchina_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/tipomacchina/update', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'codice' => ['Il campo codice è obbligatorio.'],
                    'descrizione' => ['Il campo descrizione è obbligatorio.'],
                ]
            ]);
    }

    public function test_update_tipomacchina_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'id' => '1',
            'codice' => 'STD',
            'descrizione' => 'STANDARD',
        ];

        $this->actingAs($user)->json('POST', 'tipomacchina/update', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_tipomacchina_errore_macchina()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', 'tipomacchina/' . 2 . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_tipomacchina_errore_prodotto()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', 'tipomacchina/' . 1 . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_tipomacchina_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $role = TipoMacchina::where('codice', '=', 'TIPO TEST')->first();
        $id = $role->id;

        $this->actingAs($user)->json('POST', 'tipomacchina/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_get_tipomacchina()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/tipomacchina/' . 1);

        $response->assertStatus(200);
    }
}
