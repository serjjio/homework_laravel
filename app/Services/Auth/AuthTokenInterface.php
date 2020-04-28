<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;

interface AuthTokenInterface
{
    public function generate(Token $token): string;

    public function check(string $token): bool;

    public function getToken(Request $request): ?string;
}
