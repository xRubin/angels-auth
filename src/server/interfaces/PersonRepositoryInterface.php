<?php

namespace angels2\auth\interfaces;

use angels2\auth\entities\Person as Entity;

interface PersonRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @return Entity|null
     */
    public function findOne($params = []);

    /**
     * @param $data
     * @return Entity
     */
    public function create($data);
}