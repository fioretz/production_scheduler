<?php

namespace Tests\Feature;

use App\Http\Controllers\OrdineProduzioneController;
use App\Models\OrdineProduzione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrdineProduzioneTest extends TestCase
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
            ->get('/ordineproduzione');

        $response->assertStatus(200);
        $response->assertSee('ordineproduzione');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/ordineproduzione');

        $response->assertStatus(302);
    }

    public function test_store_ordineproduzione_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/ordineproduzione/store', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'numeroordine' => ['Il campo numeroordine è obbligatorio.'],
                    'quantita' => ['Il campo quantita è obbligatorio.'],
                    'datascadenza' => ['Il campo datascadenza è obbligatorio.'],
                    'prodotto_id' => ['Il campo prodotto id è obbligatorio.'],
                ]
            ]);
    }

    public function test_store_prodotto_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'numeroordine' => '100',
            'quantita' => '500',
            'datascadenza' => '2021-06-01',
            'prodotto_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'ordineproduzione/store', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_store_prodotto_errore_numeroordine()
    {
        /** @var User $user */
        $user = User::find(2);

        $prodottoData = [
            'numeroordine' => '100',
            'quantita' => '500',
            'datascadenza' => '2021-06-01',
            'prodotto_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'ordineproduzione/store', $prodottoData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_ordineproduzione_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/ordineproduzione/update', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'quantita' => ['Il campo quantita è obbligatorio.'],
                    'datascadenza' => ['Il campo datascadenza è obbligatorio.'],
                    'prodotto_id' => ['Il campo prodotto id è obbligatorio.'],
                ]
            ]);
    }

    public function test_update_ordineproduzione_errore_numeroordine()
    {
        /** @var User $user */
        $user = User::find(2);

        $ordineProduzione = OrdineProduzione::where('numeroordine', '=', '100')->first();
        $id = $ordineProduzione->id;

        $ordineData = [
            'id' => $id,
            'numeroordine' => '200',
            'quantita' => '500',
            'datascadenza' => '2021-06-01',
            'prodotto_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'ordineproduzione/update', $ordineData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_ordineproduzione_errore_statoproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $ordineProduzione = OrdineProduzione::where('numeroordine', '=', '100')->first();
        $ordineProduzione->stato = OrdineProduzione::STATO_IN_PRODUZIONE;
        $ordineProduzione->update();

        $id = $ordineProduzione->id;

        $ordineData = [
            'id' => $id,
            'quantita' => '600',
            'datascadenza' => '2021-06-01',
            'prodotto_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'ordineproduzione/update', $ordineData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_ordineproduzione_errore_ordineinproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $ordineProduzione = OrdineProduzione::where('numeroordine', '=', '100')->first();
        $id = $ordineProduzione->id;

        $this->actingAs($user)->json('POST', 'ordineproduzione/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_ordineproduzione_errore_statochiuso()
    {
        /** @var User $user */
        $user = User::find(2);

        $ordineProduzione = OrdineProduzione::where('numeroordine', '=', '100')->first();
        $ordineProduzione->stato = OrdineProduzione::STATO_CHIUSO;
        $ordineProduzione->update();

        $id = $ordineProduzione->id;

        $ordineData = [
            'id' => $id,
            'quantita' => '600',
            'datascadenza' => '2021-06-01',
            'prodotto_id' => '1',
        ];

        $this->actingAs($user)->json('POST', 'ordineproduzione/update', $ordineData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_ordineproduzione_errore_ordinechiuso()
    {
        /** @var User $user */
        $user = User::find(2);

        $ordineProduzione = OrdineProduzione::where('numeroordine', '=', '100')->first();
        $id = $ordineProduzione->id;

        $this->actingAs($user)->json('POST', 'ordineproduzione/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_ordineproduzione_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $ordineProd = new OrdineProduzione();
        $ordineProd->numeroordine = '200';
        $ordineProd->quantita = '100';
        $ordineProd->datascadenza = '2021-06-01';
        $ordineProd->prodotto_id = '1';
        $ordineProd->tempoproduzione = '10000';
        $ordineProd->stato = 'A';
        $ordineProd->save();

        $ordineProduzione = OrdineProduzione::where('numeroordine', '=', '200')->first();
        $id = $ordineProduzione->id;

        $this->actingAs($user)->json('POST', 'ordineproduzione/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_get_ordineproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/ordineproduzione/getordineproduzionebyid/' . 1);

        $response->assertStatus(200);
    }

    public function test_get_ultimonumeroproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/ordineproduzione/ultimonumeroordine');

        $response->assertStatus(200);
    }

    public function test_get_tempoproduzione()
    {
        /** @var User $user */
        $user = User::find(2);

        $data = [
            'prodotto_id' => '1',
            'quantita' => '100'
        ];

        $this->actingAs($user)->json('POST', '/ordineproduzione/tempoproduzione', $data, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
