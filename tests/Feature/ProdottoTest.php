<?php

namespace Tests\Feature;

use App\Models\Prodotto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProdottoTest extends TestCase
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
            ->get('/prodotto');

        $response->assertStatus(200);
        $response->assertSee('prodotto');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/prodotto');

        $response->assertStatus(302);
    }

    public function test_store_prodotto_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/prodotto/store', ['Accept' => 'application/json'])
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

    public function test_store_prodotto_errore_tempoproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'codice' => 'PR TEST',
            'descrizione' => 'PROD TEST',
            'tipomacchina_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'prodotto/store', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_store_prodotto_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'codice' => 'PR TEST',
            'descrizione' => 'PROD TEST',
            'tipomacchina_id' => '1',
            'ore' => 1,
            'minuti' => 20,
            'secondi' => 40
        ];

        $this->actingAs($user)->json('POST', 'prodotto/store', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_prodotto_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/prodotto/update', ['Accept' => 'application/json'])
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

    public function test_update_prodotto_errore_tempoproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'id' => '1',
            'codice' => 'CP69',
            'descrizione' => 'Corpo Morsetto STD',
            'tipomacchina_id' => '1'
        ];

        $this->actingAs($user)->json('POST', 'prodotto/update', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_prodotto_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'id' => '1',
            'codice' => 'CP69',
            'descrizione' => 'Corpo Morsetto STD',
            'tipomacchina_id' => '1',
            'ore' => 0,
            'minuti' => 3,
            'secondi' => 20
        ];

        $this->actingAs($user)->json('POST', 'prodotto/update', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_prodotto_errore_tempoproduzione_2()
    {
        /** @var User $user */
        $user = User::find(2);

        $userData = [
            'id' => '2',
            'codice' => 'D4S',
            'descrizione' => 'Diametro MM 4 Small',
            'tipomacchina_id' => '1',
            'ore' => 1,
            'minuti' => 3,
            'secondi' => 20
        ];

        $this->actingAs($user)->json('POST', 'prodotto/update', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_prodotto_errore_deleting()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', 'prodotto/' . 2 . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_prodotto_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $role = Prodotto::where('codice', '=', 'PR TEST')->first();
        $id = $role->id;

        $this->actingAs($user)->json('POST', 'prodotto/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_get_prodotto()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/prodotto/' . 1);

        $response->assertStatus(200);
    }
}
