<?php

namespace Domain\EventSubscribers;

use Domain\Repositories\ComposedUserRepository;
use Drift\Websocket\Connection\Connection;
use Drift\Websocket\Event\WebsocketConnectionOpened;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastNewConnections implements EventSubscriberInterface
{

    protected $userRepository;

    public function __construct(ComposedUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function broadcastNewConnection(WebsocketConnectionOpened $event)
    {
        $this->userRepository->all()->then(function ($users) use ($event) {
            $event->getConnections()->broadcast(json_encode([
                'type' => 'new_connection',
                'data' => [
                    'connection' => Connection::getConnectionHash($event->getNewConnection()),
                    'users' => array_values(array_map(function ($user) {
                            return $user->toArray();
                        }, $users))
                ]
            ]));
        });
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