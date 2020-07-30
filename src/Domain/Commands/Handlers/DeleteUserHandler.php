<?php

namespace Domain\Commands\Handlers;

use Domain\Commands\DeleteUserCommand;
use Domain\Events\UserDeleted;
use Domain\Repositories\UserRepository;
use Drift\EventBus\Bus\EventBus;
use React\Promise\PromiseInterface;

class DeleteUserHandler
{
    private $userRepository;
    private $eventBus;

    public function __construct(UserRepository $userRepository, EventBus $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
    }

    public function handle(DeleteUserCommand $command): PromiseInterface
    {
        return $this->userRepository
            ->delete($command->getUser())
            ->then(function () use ($command) {
                return $this->eventBus->dispatch(new UserDeleted($command->getUser()));
            });
    }
}