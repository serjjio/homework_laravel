<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthTokenInterface;
use Closure;
use Illuminate\Auth\AuthenticationException;

class AuthJwt
{
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
        $token = $this->authToken->getToken($request);
        if (!$this->authToken->check($token)) {
            throw new AuthenticationException();
        }

        return $next($request);
    }
}
