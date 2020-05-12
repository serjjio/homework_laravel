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
     * @param User $user
     * @param \DateTime $exp
     * @param string $role
     */
    public function __construct(User $user, \DateTime $exp, string $role)
    {
        $this->iat = now()->timestamp;
        $this->exp = $exp->getTimestamp();
        $this->role = $role;
        $this->user = $user->getAuthIdentifier();
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
}
