<?php

namespace Domain\Commands\Handlers;

use Domain\Commands\UpdateUserCommand;
use Domain\Events\UserSaved;
use Domain\Repositories\UserRepository;
use Drift\EventBus\Bus\EventBus;
use React\Promise\PromiseInterface;

class UpdateUserHandler
{
    private $userRepository;

    private $eventBus;

    public function __construct(UserRepository $userRepository, EventBus $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
    }

    public function handle(UpdateUserCommand $command): PromiseInterface
    {
        return $this->userRepository
            ->save($command->getUser())
            ->then(function () use ($command) {
                $this->eventBus->dispatch(new UserSaved($command->getUser()));
            });
    }
}