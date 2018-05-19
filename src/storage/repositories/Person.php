<?php

namespace angels2\auth\storage\repositories;

use angels2\auth\storage\interfaces\PersonRepositoryInterface;
use Tarantool\Mapper\Repository;

/**
 * Class PersonRepository
 */
class Person extends Repository implements PersonRepositoryInterface
{
    public $engine = 'memtx'; // or vinyl

    public $indexes = [
        [
            'type' => 'hash',
            'fields' => ['id']
        ],
        ['login'],
        ['session'],
        [
            'fields' => ['created'],
            'unique' => false
        ],
    ];

    /**
     * @return int
     */
    public function getCount(): int
    {
        return (int)$this->getMapper()->getClient()->call('box.space.person:count')->getData()[0];
    }
}