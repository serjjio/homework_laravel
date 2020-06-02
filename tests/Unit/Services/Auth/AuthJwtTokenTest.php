<?php

namespace Tests\Unit\Services\Auth;

use App\Services\Auth\AuthJwtToken;
use App\Services\Auth\Token;
use App\User;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

class AuthJwtTokenTest extends TestCase
{

    private $authJwtToken;

    protected function setUp(): void
    {
        $this->authJwtToken = new AuthJwtToken();
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
}
