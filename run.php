<?php

require_once __DIR__ . '/vendor/autoload.php';

use Tarantool\Client;
use Tarantool\Mapper;

use angels2\auth\storage\entities;
use angels2\auth\storage\repositories;
use angels2\auth\server\HttpServer;
use angels2\auth\server\RequestDispatcher;
use angels2\auth\storage\security\SecurityDefault;
use angels2\auth\processors\actions;

$tarantoolClient = new Client\Client(new Client\Connection\StreamConnection(), new Client\Packer\PurePacker());
$tarantoolMapper = new Mapper\Mapper($tarantoolClient);

$tarantoolMapper->getPlugin(Mapper\Plugin\Annotation::class)
    ->register(entities\Person::class)
    ->register(repositories\Person::class)
    ->migrate();

$tarantoolMapper->getPlugin(Mapper\Plugin\Annotation::class);

$security = new SecurityDefault();

$dispatcher = (new RequestDispatcher())
    ->addRoute('/person/login', new actions\person\login\Processor($security, $tarantoolMapper->getRepository('person')))
    ->addRoute('/person/create', new actions\person\create\Processor($security, $tarantoolMapper->getRepository('person')))
    ->addRoute('/server/stat', new actions\server\stat\Processor($tarantoolMapper->getRepository('person')));

(new HttpServer('127.0.0.1', 9100, $dispatcher))
    ->setLogFile(__DIR__ . '/../logs/angels.auth.log')
    ->start();