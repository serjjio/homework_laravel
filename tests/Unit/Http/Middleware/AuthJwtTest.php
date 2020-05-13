<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\AuthJwt;
use App\Services\Auth\AuthTokenInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class AuthJwtTest extends TestCase
{
    /**
     * @var AuthJwt
     */
    private $authJwt;

    /**
     * @var Request|\PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

    /**
     * @var AuthTokenInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $authToken;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->authToken = $this->createMock(AuthTokenInterface::class);
        $this->authJwt = new AuthJwt($this->authToken);

        parent::setUp();
    }

    /**
     * @test
     */
    public function handleEmptyToken()
    {
        $this->authToken
            ->expects($this->once())
            ->method('getToken')
            ->with($this->request)
            ->willReturn(null);

        $next = function () {};

        $this->assertInstanceOf(AuthJwt::class, $this->authJwt);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Unauthenticated.');
        $this->expectExceptionMessageMatches('[Una*]');
        $this->expectExceptionCode(0);

        $this->authJwt->handle($this->request, $next);
    }

    /**
     * @test
     */
    public function handleUnexpectedValueToken()
    {
        $token = 'token';
        $this->authToken
            ->expects($this->once())
            ->method('getToken')
            ->with($this->request)
            ->willReturn($token);

        $this->authToken
            ->expects($this->once())
            ->method('check')
            ->with($token)
            ->willReturn(false);

        $next = function () {};

        $this->assertInstanceOf(AuthJwt::class, $this->authJwt);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Unauthenticated.');
        $this->expectExceptionMessageMatches('[Una*]');
        $this->expectExceptionCode(0);

        $this->authJwt->handle($this->request, $next);
    }

    /**
     * @test
     */
    public function handleUnexpectedValueException()
    {
        $token = 'token';
        $exceptionMessage = 'Custom message exception';

        $this->authToken
            ->expects($this->once())
            ->method('getToken')
            ->with($this->request)
            ->willReturn($token);

        $this->authToken
            ->expects($this->once())
            ->method('check')
            ->with($token)
            ->willThrowException(new \UnexpectedValueException($exceptionMessage));

        $next = function () {};

        $this->assertInstanceOf(AuthJwt::class, $this->authJwt);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage($exceptionMessage);
        $this->expectExceptionCode(0);

        $this->authJwt->handle($this->request, $next);
    }

    /**
     * @test
     */
    public function handle()
    {
        $token = 'token';

        $this->authToken
            ->expects($this->once())
            ->method('getToken')
            ->with($this->request)
            ->willReturn($token);

        $this->authToken
            ->expects($this->once())
            ->method('check')
            ->with($token)
            ->willReturn(true);

        $next = function ($request) {
            $this->assertInstanceOf(Request::class, $request);
        };

        $this->assertInstanceOf(AuthJwt::class, $this->authJwt);

        $this->authJwt->handle($this->request, $next);

        $this->assertEquals(4, $this->authJwt->getCountCallHandle());
    }
}
