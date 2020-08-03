<?php

namespace Domain\Repositories;

use Domain\Exceptions\UserNotFoundException;
use Domain\Models\User;
use React\Promise\PromiseInterface;

use function React\Promise\reject;
use function React\Promise\resolve;

class InMemoryUserRepository implements UserRepository
{
    protected $users = [];

    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    public function save(User $user): PromiseInterface
    {
        $this->users[$user->getId()] = $user;

        return resolve();
    }

    public function find(int $id): PromiseInterface
    {
        return isset($this->users[$id])
            ? resolve($this->users[$id])
            : reject(new UserNotFoundException());
    }

    public function delete(User $user): PromiseInterface
    {
        if (isset($this->users[$user->getId()])) {
            unset($this->users[$user->getId()]);
            return resolve(true);
        }

        return reject(new UserNotFoundException());
    }

    public function all(): PromiseInterface
    {
        return resolve($this->users);
    }
}