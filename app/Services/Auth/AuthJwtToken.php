<?php

namespace App\Services\Auth;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthJwtToken implements AuthTokenInterface
{
    private const ALG = 'HS256';
    private const HEADER = 'A';
    private $key = 'key';


    public function generate(Token $token): string
    {
        return JWT::encode($token->getPayload(), $this->key, self::ALG);
    }

    public function check(string $token): bool
    {
        $payload = JWT::decode($token, $this->key, [self::ALG]);

        return !empty($payload);
    }

    public function getToken(Request $request): ?string
    {
        if (!$request->hasHeader(self::HEADER)) {
            return null;
        }

        $token = $request->header(self::HEADER);

        return $token;
    }
}
