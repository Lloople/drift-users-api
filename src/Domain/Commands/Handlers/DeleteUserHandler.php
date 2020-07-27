<?php

namespace Domain\Commands\Handlers;

use Domain\Commands\DeleteUserCommand;
use Domain\Repositories\UserRepository;
use React\Promise\PromiseInterface;

class DeleteUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(DeleteUserCommand $command): PromiseInterface
    {
        return $this->userRepository->delete($command->getUser());
    }
}