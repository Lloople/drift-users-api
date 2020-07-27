<?php

namespace Domain\Commands\Handlers;

use Domain\Commands\UpdateUserCommand;
use Domain\Repositories\UserRepository;
use React\Promise\PromiseInterface;

class UpdateUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(UpdateUserCommand $command): PromiseInterface
    {
        return $this->userRepository->save($command->getUser());
    }
}