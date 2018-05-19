<?php

namespace angels2\auth\requests\tarantool;

use Tarantool\Client\IProto;
use Tarantool\Client\Request\Request;

class CountRequest implements Request
{
    private $spaceId;
    private $values;

    public function __construct($spaceId)
    {
        $this->spaceId = $spaceId;
    }

    public function getType()
    {
        return self::TYPE_INSERT;
    }

    public function getBody()
    {
        return [
            IProto::SPACE_ID => $this->spaceId,
            IProto::TUPLE => $this->values,
        ];
    }
}