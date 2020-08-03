<?php

namespace Domain\EventSubscribers;

use Domain\Events\UserSaved;
use Domain\Events\UserDeleted;
use Domain\Repositories\ComposedUserRepository;
use Drift\HttpKernel\Event\DomainEventEnvelope;
use Drift\Websocket\Connection\Connections;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastDomainEvents implements EventSubscriberInterface
{

    private $connections;

    private $userRepository;

    public function __construct(Connections $eventsConnections, ComposedUserRepository $userRepository)
    {
        $this->connections = $eventsConnections;
        $this->userRepository = $userRepository;
    }

    public function broadcastUserSaved(DomainEventEnvelope $eventEnvelope)
    {
        $userSavedEvent = $eventEnvelope->getDomainEvent();

        $this->connections->broadcast(json_encode([
            'type' => 'user_saved',
            'data' => [
                'user' => $userSavedEvent->getUser()->toArray(),
                'database' => $this->userRepository->all()
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
                'database' => $this->userRepository->all()
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