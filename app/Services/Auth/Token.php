<?php

namespace App\Services\Auth;

use App\User;

class Token
{
    private $iat;

    private $exp;

    private $role;

    private $user;

    /**
     * Token constructor.
     * @param $id
     * @param $role
     * @param $iat
     * @param $exp
     */
    public function __construct($id, $role, $iat, $exp)
    {
        $this->iat = $iat;
        $this->exp = $exp;
        $this->role = $role;
        $this->user = $id;
    }

    public static function getInstance(User $user, \DateTime $exp, string $role): self
    {
        return new self($user->getAuthIdentifier(), $role, now()->timestamp, $exp->getTimestamp());
    }

    public function getPayload(): array
    {
        return [
            'iat' => $this->iat,
            'exp' => $this->exp,
            'role' => $this->role,
            'user' => $this->user,
        ];
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
