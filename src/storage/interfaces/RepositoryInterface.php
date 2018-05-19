<?php

namespace angels2\auth\storage\interfaces;

interface RepositoryInterface
{
    /**
     * @return int
     */
    public function getCount(): int;
}