<?php

namespace App\Services\Auth;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthJwtToken implements AuthTokenInterface
{
    private const ALG = 'HS256';
    private const HEADER = 'Authorization';
    private $key = 'key';


    public function generate(Token $token): string
    {
        return JWT::encode($token->getPayload(), $this->key, self::ALG);
    }

    public function check(string $token): bool
    {
        /** @var  $payload */
        $payload = JWT::decode($token, $this->key, [self::ALG]);

        if (empty($payload) || empty($payload->user)) {
            return false;
        }

        $user = User::query()->find($payload->user);

        if (!$user instanceof User || !$user->enabled) {
            return false;
        }

        return true;
    }

    public function getToken(Request $request): ?string
    {
        if (!$request->hasHeader(self::HEADER)) {
            return null;
        }

        return $request->bearerToken();
    }

    public function getTokenObject(Request $request): ?Token
    {
        $token = $this->getToken($request);

        /** @var  $payload */
        $payload = JWT::decode($token, $this->key, [self::ALG]);

        if (empty($payload) || empty($payload->user)) {
            return null;
        }

        return new Token($payload->user, $payload->role, $payload->iat, $payload->exp);
    }

    public function getUser(string $token): ?User
    {
        /** @var  $payload */
        $payload = JWT::decode($token, $this->key, [self::ALG]);

        if (empty($payload) || empty($payload->user)) {
            return null;
        }

        $user = User::query()->find($payload->user);

        if (!$user instanceof User || !$user->enabled) {
            return null;
        }

        return $user;
    }
}
