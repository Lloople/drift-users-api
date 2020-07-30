<?php

namespace Domain\Repositories;

use Domain\Events\UserDeleted;
use Domain\Events\UserSaved;
use Domain\Models\User;
use Drift\HttpKernel\AsyncKernelEvents;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ComposedUserRepository implements UserRepository, EventSubscriberInterface
{
    protected $memory;

    protected $dbal;

    public function __construct(InMemoryUserRepository $memory, DBALUserRepository $dbal)
    {
        $this->memory = $memory;
        $this->dbal = $dbal;
    }

    public function find(int $id): PromiseInterface
    {
        return $this->memory->find($id);
    }

    public function save(User $user): PromiseInterface
    {
        return $this->dbal->save($user);
    }

    public function delete(User $user): PromiseInterface
    {
        return $this->dbal->delete($user);
    }

    public function cache()
    {
        $this->dbal->all()->then(function($users) {
            $this->memory = new InMemoryUserRepository($users); 
        });
    }

    public static function getSubscribedEvents()
    {
        return [
            UserSaved::class => [
                ['cache', 0]
            ],
            UserDeleted::class => [
                ['cache', 0]
            ],
            AsyncKernelEvents::PRELOAD => [
                'cache', 0
            ]
        ];
    }
}