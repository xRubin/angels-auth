<?php

namespace angels2\auth\storage\entities;

use Tarantool\Mapper\Entity;

class Person extends Entity
{
    const ROLE_BLOCKED = 'blocked';

    /** @var string */
    public $id;
    /** @var string */
    public $login;
    /** @var string */
    public $password;
    /** @var string */
    public $source;
    /** @var string */
    public $ip;
    /** @var string */
    public $session;
    /** @var array */
    public $roles;
    /** @var integer */
    public $created;

    public function beforeCreate(): void
    {
        $this->created = time();
    }
}