<?php

namespace Tests\Feature;

use App\Services\Auth\AuthJwtToken;
use App\Services\Auth\Token;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function getTokenForbiddenByUser()
    {
        $userId = 111;
        $role = 'admin';
        $user = factory(User::class)->create([
            'id' => $userId,
            'enabled' => false,
            'role' => $role,
        ]);

        $response = $this->post('/api/auth/',['name' => $user->name, 'email' => $user->email]);
        $response->assertStatus(500);
    }

    /**
     * @test
     */
    public function getTokenByUser()
    {
        $userId = 111;
        $role = 'admin';
        $email = 'admin@test.com';
        $password = hash('sha256', '123');
        $user = factory(User::class)->create([
            'id' => $userId,
            'enabled' => false,
            'role' => $role,
            'email' => $email,
            'password' => $password,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');

        $response = $this->postJson('/api/auth/',['email' => $user->email, 'password' => '123']);

        $response->assertStatus(200);

        $token = $response->getContent();
        $token = json_decode($token);
        $response->assertJsonFragment([$token->token]);
    }

    public function getToken(User $user): string
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+1 hour');

        $token = Token::getInstance($user, $dateExt, $user->getRole());
        $auth = new AuthJwtToken();

        return $auth->generate($token);
    }
}
