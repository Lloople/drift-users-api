<?php

namespace Domain\EventSubscribers;

use Drift\Websocket\Connection\Connection;
use Drift\Websocket\Event\WebsocketConnectionClosed;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastCloseConnections implements EventSubscriberInterface
{
    public function broadcastClosedConnections(WebsocketConnectionClosed $event)
    {
        $event->getConnections()->broadcast(json_encode([
            'type' => 'closed_connection',
            'data' => [
                'connection' => Connection::getConnectionHash($event->getConnection())
            ]
        ]));
    }

    public static function getSubscribedEvents()
    {
        return [
            WebsocketConnectionClosed::class => [
                ['broadcastClosedConnections', 0]
            ]
        ];
    }
}