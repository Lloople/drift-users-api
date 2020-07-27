<?php

namespace Tests\Domain\Repositories;

use Domain\Repositories\InMemoryUserRepository;
use Domain\Repositories\UserRepository;

class InMemoryUserRepositoryTest extends UserRepositoryTest
{
    protected function createRepository(array $users = []): UserRepository
    {
        return new InMemoryUserRepository($users);
    }
}