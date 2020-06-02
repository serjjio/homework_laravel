<?php

namespace Tests\Feature\Services\Auth;

use App\City;
use App\Services\Auth\AuthJwtToken;
use App\Services\Auth\Token;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthJwtTokenTest extends TestCase
{
    use RefreshDatabase;

    private $authJwtToken;

    protected function setUp(): void
    {
        $this->authJwtToken = new AuthJwtToken();

        parent::setUp();
    }

    /**
     * @test
     */
    public function auth()
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $token = new Token(1, 'admin', now()->timestamp, $dateExt->getTimestamp());

        $tokenStr = $this->authJwtToken->generate($token);

        $this->assertNotEmpty($tokenStr);
        $this->assertIsString($tokenStr);
    }

    /**
     * @test
     */
    public function checkUnexpectedValueException()
    {
        $token = '';

        $this->expectException(\UnexpectedValueException::class);
        $this->authJwtToken->check($token);
    }

    /**
     * @test
     */
    public function check()
    {
        $role = 'admin';
        $userId = 111;

        factory(User::class)->create([
            'id' => $userId,
            'enabled' => true,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $srtToken = $this->authJwtToken->generate($token);

        $res = $this->authJwtToken->check($srtToken);

        $this->assertTrue($res);
    }

    /**
     * @test
     */
    public function checkEmptyUserInToken()
    {
        $role = 'admin';
        $userId = '';

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $srtToken = $this->authJwtToken->generate($token);

        $res = $this->authJwtToken->check($srtToken);

        $this->assertFalse($res);
    }

    /**
     * @test
     */
    public function checkUserDisabled()
    {
        $role = 'admin';
        $userId = 111;

        factory(User::class)->create([
            'id' => $userId,
            'enabled' => false,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $srtToken = $this->authJwtToken->generate($token);

        $res = $this->authJwtToken->check($srtToken);

        $this->assertFalse($res);
    }
}
