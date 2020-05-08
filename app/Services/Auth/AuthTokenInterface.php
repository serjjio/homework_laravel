<?php

namespace App\Services\Auth;

use App\User;
use Illuminate\Http\Request;

interface AuthTokenInterface
{
    public function generate(Token $token): string;

    public function check(string $token): bool;

    public function getToken(Request $request): ?string;

    public function getTokenObject(Request $request): ?Token;

    public function getUser(string $token): ?User;
}
