<?php


namespace App\Services\Role;


use App\Services\Auth\AuthTokenInterface;

class CheckUserRole
{
    private $authJwtToken;

    /**
     * CheckUserRole constructor.
     * @param AuthTokenInterface $authJwtToken
     */
    public function __construct(AuthTokenInterface $authJwtToken)
    {
        $this->authJwtToken = $authJwtToken;
    }

    public function check($token, $role): bool
    {
        //$user = $this->authJwtToken->getUser($token);

        if ($token->getRole() == UserRole::SUPER_ADMIN) {
            return true;
        }
        else if ($token->getRole() == UserRole::ADMIN) {
            $rolesAdmin = UserRole::getAllowedRoles(UserRole::ADMIN);

            if (in_array($role, $rolesAdmin)) {
                return true;
            }
        }
        return $token->getRole() == $role;


    }

}
