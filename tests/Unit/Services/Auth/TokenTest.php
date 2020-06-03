<?php


namespace Tests\Unit\Services\Auth;


use App\Services\Auth\Token;
use App\User;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    private $user;
    protected function setUp(): void
    {
        $this->user = $this->createMock(User::class);

        parent::setUp();
    }

    /**
     *@test
     */
    public function getRole()
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $token = new Token(1, 'admin', now()->timestamp, $dateExt);

        $role = $token->getRole();
        $this->assertEquals('admin', $role);
    }




}
