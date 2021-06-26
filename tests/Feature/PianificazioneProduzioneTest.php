<?php

namespace Tests\Feature;

use App\Models\OrdineProduzione;
use App\Models\PianificazioneProduzioneTesta;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PianificazioneProduzioneTest extends TestCase
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
            ->get('/pianificazioneproduzione/creapianificazioneform');

        $response->assertStatus(200);
        $response->assertSee('creapianificazioneform');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/pianificazioneproduzione/creapianificazioneform');

        $response->assertStatus(302);
    }

    public function test_show_pianificazioni_elenco()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/pianificazioneproduzione');

        $response->assertStatus(200);
        $response->assertSee('pianificazioneproduzione');
    }

    public function test_show_pianificazioni_elenco_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/pianificazioneproduzione');

        $response->assertStatus(302);
    }

    public function test_get_pianificazione_by_id()
    {
        /** @var User $user */
        $user = User::find(2);

        $pianificazioneProduzione = DB::table('pianificazione_produzione_testa')->first();
        $id = $pianificazioneProduzione->id;

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/pianificazioneproduzione/' . $id);

        $response->assertStatus(200);
    }

    public function test_get_pianificazione_by_id_notfound()
    {
        /** @var User $user */
        $user = User::find(2);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/pianificazioneproduzione/' . 0);

        $response->assertStatus(200);
    }

    public function test_crea_pianificazione_mancano_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/pianificazioneproduzione/creapianificazione', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'nome' => ['Il campo nome è obbligatorio.'],
                    'datainizio' => ['Il campo datainizio è obbligatorio.'],
                ]
            ]);
    }

    public function test_crea_pianificazione_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $pianificazioneData = [
            'nome' => 'TEST',
            'datainizio' => '2021-06-01'
        ];

        $this->actingAs($user)->json('POST', '/pianificazioneproduzione/creapianificazione', $pianificazioneData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_pianificazione_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $pianificazione = PianificazioneProduzioneTesta::where('nome', '=', 'TEST')->first();
        $id = $pianificazione->id;

        $this->actingAs($user)->json('POST', 'pianificazioneproduzione/' . $id . '/delete', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
