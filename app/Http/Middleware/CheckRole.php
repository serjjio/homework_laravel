<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthTokenInterface;
use Closure;
use Illuminate\Auth\AuthenticationException;

class CheckRole
{
    const S_A = 'sa';
    const A = 'a';
    const E = 'e';

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
    public function handle($request, Closure $next, string $role)
    {
        $token = $this->authToken->getTokenObject($request);

        //$token->getRole() = 'admin';
        //$role = 'editor'


        $admin = [
            'editor',
            'manager',
        ];


        if ($token->getRole() != $role) {
            throw new AuthenticationException('User role do not has permission');
        }

        return $next($request);
    }
}
