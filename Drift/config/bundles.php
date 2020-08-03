<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Drift\CommandBus\CommandBusBundle::class => ['all' => true],
    Drift\DBAL\DBALBundle::class => ['all' => true],
    Drift\EventBus\EventBusBundle::class => ['all' => true],
    Drift\Websocket\WebsocketBundle::class => ['all' => true],
];
