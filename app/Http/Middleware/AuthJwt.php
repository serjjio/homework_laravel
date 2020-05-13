<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthTokenInterface;
use Closure;
use Illuminate\Auth\AuthenticationException;
use UnexpectedValueException;

class AuthJwt
{
    /**
     * @var int
     */
    private static $countCallHandle;

    /**
     * @var AuthTokenInterface
     */
    private $authToken;

    /**
     * AuthJwt constructor.
     * @param AuthTokenInterface $authToken
     */
    public function __construct(AuthTokenInterface $authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        self::$countCallHandle++;
        $token = $this->authToken->getToken($request);

        try {
            if (empty($token) || !$this->authToken->check($token)) {
                throw new AuthenticationException();
            }
        } catch (UnexpectedValueException $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return $next($request);
    }

    public function getCountCallHandle(): int
    {
        return self::$countCallHandle;
    }
}
