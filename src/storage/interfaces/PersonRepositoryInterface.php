<?php

namespace angels2\auth\storage\interfaces;

use angels2\auth\storage\entities\Person;

interface PersonRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @return Person|null
     */
    public function findOne($params = []);

    /**
     * @param $data
     * @return Person
     */
    public function create($data);
}