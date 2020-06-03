<?php


namespace Tests\Feature\Services\Auth;


use App\Services\Auth\AuthJwtToken;
use App\Services\Auth\Token;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use \UnexpectedValueException;
use Tests\TestCase;

class AuthJwtTokenTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    /**
     * @var AuthJwtToken
     */
    private $authJwtToken;

    /**
     * @var Request|\PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

    public function setUp(): void
    {
        $this->authJwtToken = new AuthJwtToken();
        $this->request = $this->createMock(Request::class);

        parent::setUp();
    }

    /**
     * @test
     */
    public function getTokenEmptyObject()
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $unitId = '';
        $role = 'admin';
        $token = new Token($unitId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);

        $this->request
            ->expects($this->once())
            ->method('hasHeader')
            ->willReturn(true);

        $this->request
            ->expects($this->once())
            ->method('bearerToken')
            ->willReturn($tokenStr);
        $user = $this->authJwtToken->getTokenObject($this->request);
        $this->assertEmpty($user);
    }

    /**
     * @test
     */
    public function getUserEmpty()
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $unitId = '';
        $role = 'admin';

        $token = new Token($unitId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);
        $user = $this->authJwtToken->getUser($tokenStr);
        $this->assertEmpty($user);
    }

    /**
     * @test
     */
    public function getDisabledUser()
    {
        $userId = 111;
        $role = 'admin';
        factory(User::class)->create([
            'id' => $userId,
            'enabled' => false,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');


        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);
        $user = $this->authJwtToken->getUser($tokenStr);
        $this->assertEmpty($user);
    }

    /**
     * @test
     */
    public function getUser()
    {
        $userId = 111;
        $role = 'admin';
        factory(User::class)->create([
            'id' => $userId,
            'enabled' => true,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');


        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);
        $user = $this->authJwtToken->getUser($tokenStr);
        $this->assertInstanceOf(User::class, $user);
    }


    /**
     * @test
     */
    public function getToken()
    {
        $this->request
            ->expects($this->once())
            ->method('hasHeader')
            ->willReturn(true);

        $this->request
            ->expects($this->once())
            ->method('bearerToken')
            ->willReturn('token');
        //$this->request->header('Authorization', 'Bearer SomeToken');
        $token = $this->authJwtToken->getToken($this->request);
        $this->assertIsString($token);
    }

    /**
     * @test
     */
    public function getTokenEmptyHeader()
    {
        $token = $this->authJwtToken->getToken($this->request);
        $this->assertEmpty($token);
    }


    /**
     * @test
     */
    public function generateToken()
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
     *
     * @dataProvider  checkTokenProvider
     */
    public function checkTokenUnexpectedValueException(string $token)
    {
        $this->expectException(UnexpectedValueException::class);
        $this->authJwtToken->check($token);


    }

    /**
     * @test
     */
    public function checkEmptyUser()
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');
        $unitId = '';
        $role = 'admin';

        $token = new Token($unitId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);
        $result = $this->authJwtToken->check($tokenStr);
        $this->assertFalse($result);


    }

    /**
     * @test
     */
    public function checkDisabledUser()
    {
        $userId = 111;
        $role = 'admin';
        factory(User::class)->create([
            'id' => $userId,
            'enabled' => false,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');


        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);
        $result = $this->authJwtToken->check($tokenStr);
        $this->assertFalse($result);


    }

    /**
     * @test
     */
    public function check()
    {
        $userId = 111;
        $role = 'admin';
        factory(User::class)->create([
            'id' => $userId,
            'enabled' => true,
        ]);

        $dateExt = new \DateTime();
        $dateExt->modify('+10 hour');


        $token = new Token($userId, $role, now()->timestamp, $dateExt->getTimestamp());
        $tokenStr = $this->authJwtToken->generate($token);
        $result = $this->authJwtToken->check($tokenStr);
        $this->assertTrue($result);


    }

    public function checkTokenProvider()
    {
        return [
            'TOKEN' => ['token'],
        ];
    }
}
