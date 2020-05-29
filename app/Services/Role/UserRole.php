<?php


namespace App\Services\Role;


class UserRole
{
    //'super_admin', 'admin', 'editor', 'visitor', 'anonymous'
    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const EDITOR = 'editor';
    const ANONYMOUS = 'anonymous';

    protected static $roleHierarchy = [
      self::SUPER_ADMIN => ['*'],
      self::ADMIN => [
          self::EDITOR,
          self::ANONYMOUS,
      ],
      self::EDITOR => [],
      self::ANONYMOUS => [],
    ];

    public static function getAllowedRoles(string $role)
    {
        if (isset(self::$roleHierarchy[$role])) {
            return self::$roleHierarchy[$role];
        }
        return [];
    }
}
