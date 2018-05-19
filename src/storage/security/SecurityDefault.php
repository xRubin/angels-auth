<?php
namespace angels2\auth\storage\security;

use angels2\auth\storage\interfaces\SecurityInterface;

class SecurityDefault implements SecurityInterface
{
    /**
     * @param string $password
     * @return string
     */
    public function cryptPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validatePassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}