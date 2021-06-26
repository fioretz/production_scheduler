<?php


namespace Tests\Feature;


use App\Models\User;
use Tests\TestCase;

class ProfiloTest extends TestCase
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
            ->get('/profilo');

        $response->assertStatus(200);
        $response->assertSee('profilo');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_not_logged()
    {
        $response = $this->withSession(['banned' => false])
            ->get('/profilo');

        $response->assertStatus(302);
    }

    public function test_update_profilo_namemail_mancano_tutti_i_dati()
    {
        /** @var User $user */
        $user = User::find(2);

        $this->actingAs($user)->json('POST', '/profilo/updatenamemail', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['Il campo name è obbligatorio.'],
                    'email' => ['Il campo email è obbligatorio.'],
                ]
            ]);
    }

    public function test_update_profilo_namemail_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $profiloData = [
            'name' => 'admin',
            'email' => 'admin@admin.com'
        ];

        $this->actingAs($user)->json('POST', 'profilo/updatenamemail', $profiloData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_profilo_password_corretto()
    {
        /** @var User $user */
        $user = User::find(2);

        $profiloData = [
            'password' => 'admin',
            'confirm_password' => 'admin'
        ];

        $this->actingAs($user)->json('POST', 'profilo/updatepassword', $profiloData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_profilo_manca_password()
    {
        /** @var User $user */
        $user = User::find(2);

        $profiloData = [];

        $this->actingAs($user)->json('POST', 'profilo/updatepassword', $profiloData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_profilo_password_errato()
    {
        /** @var User $user */
        $user = User::find(2);

        User::saving(function () { return false; });
        User::flushEventListeners();

        $profiloData = [
            'password' => 'admin',
            'confirm_password' => 'admin'
        ];

        $this->actingAs($user)->json('POST', 'profilo/updatepassword', $profiloData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
