<?php
namespace angels2\auth\interfaces;

interface SecurityInterface
{
    /**
     * @param string $password
     * @return string
     */
    public function cryptPassword(string $password): string;

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validatePassword(string $password, string $hash): bool;
}