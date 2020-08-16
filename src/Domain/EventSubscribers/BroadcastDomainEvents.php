<?php

namespace Domain\EventSubscribers;

use Domain\Events\UserSaved;
use Domain\Events\UserDeleted;
use Drift\HttpKernel\Event\DomainEventEnvelope;
use Drift\Websocket\Connection\Connections;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastDomainEvents implements EventSubscriberInterface
{

    private $connections;

    public function __construct(Connections $eventsConnections)
    {
        $this->connections = $eventsConnections;
    }

    public function broadcastUserSaved(DomainEventEnvelope $eventEnvelope)
    {
        $userSavedEvent = $eventEnvelope->getDomainEvent();

        $this->connections->broadcast(json_encode([
            'type' => 'user_saved',
            'data' => [
                'user' => $userSavedEvent->getUser()->toArray(),
            ]
        ]));
    }

    public function broadcastUserDeleted(DomainEventEnvelope $eventEnvelope)
    {
        $userDeletedEvent = $eventEnvelope->getDomainEvent();

        $this->connections->broadcast(json_encode([
            'type' => 'user_deleted',
            'data' => [
                'user' => $userDeletedEvent->getUser()->toArray(),
            ]
        ]));
    }

    public static function getSubscribedEvents()
    {
        return [
            UserSaved::class => [
                ['broadcastUserSaved', 0]
            ],
            UserDeleted::class => [
                ['broadcastUserDeleted', 0]
            ]
        ];
    }
}