<?php

namespace Domain\Queries\Handlers;

use Domain\Queries\GetUserQuery;
use Domain\Repositories\UserRepository;
use React\Promise\PromiseInterface;


class GetUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function handle(GetUserQuery $query): PromiseInterface
    {
        return $this->userRepository->find($query->getId());
    }
}