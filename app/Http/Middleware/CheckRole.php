<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthTokenInterface;
use App\Services\Role\CheckUserRole;
use Closure;
use http\Exception\UnexpectedValueException;
use Illuminate\Auth\AuthenticationException;

class CheckRole
{
    /**
     * @var AuthTokenInterface
     */
    private $authToken;
    private $checkUserRole;

    /**
     * AuthJwt constructor.
     * @param AuthTokenInterface $authToken
     * @param CheckUserRole $checkUserRole
     */
    public function __construct(AuthTokenInterface $authToken, CheckUserRole $checkUserRole)
    {
        $this->authToken = $authToken;
        $this->checkUserRole = $checkUserRole;

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

        if (!$this->checkUserRole->check($token, $role)) {

            throw new AuthenticationException('User role do not has permission');
        }

        /*if ($token->getRole() != $role) {
            throw new AuthenticationException('User role do not has permission');
        }*/

        return $next($request);
    }
}
