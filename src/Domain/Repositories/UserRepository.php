<?php

namespace Domain\Repositories;

use Domain\Models\User;
use React\Promise\PromiseInterface;

interface UserRepository
{
    public function save(User $user): PromiseInterface;

    public function find(int $id): PromiseInterface;

    public function delete(User $user): PromiseInterface;

    public function all(): PromiseInterface;
}