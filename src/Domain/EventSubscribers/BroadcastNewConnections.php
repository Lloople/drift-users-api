<?php

namespace Domain\EventSubscribers;

use Drift\Websocket\Connection\Connection;
use Drift\Websocket\Event\WebsocketConnectionOpened;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastNewConnections implements EventSubscriberInterface
{
    public function broadcastNewConnection(WebsocketConnectionOpened $event)
    {
        $event->getConnections()->broadcast(json_encode([
            'type' => 'new_connection',
            'data' => [
                'connection' => Connection::getConnectionHash($event->getNewConnection())
            ]
        ]));
    }

    public static function getSubscribedEvents()
    {
        return [
            WebsocketConnectionOpened::class => [
                ['broadcastNewConnection', 0]
            ]
        ];
    }
}