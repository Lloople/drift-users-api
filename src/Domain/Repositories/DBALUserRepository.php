<?php

namespace Domain\Repositories;

use Domain\Exceptions\UserNotFoundException;
use Domain\Models\User;
use Drift\DBAL\Connection;
use Drift\DBAL\Result;
use React\Promise\PromiseInterface;

class DBALUserRepository implements UserRepository
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): PromiseInterface
    {
        return $this->connection->upsert('users', [
            'id' => $user->getId()
            ], [
            'name' => $user->getName()
        ]);
    }

    public function find(int $id): PromiseInterface
    {
        return $this->connection->query(
            $this->connection
                ->createQueryBuilder()
                ->select('*')
                ->from('users')
                ->where('id = :id')
                ->setParameter('id', $id)
            )->then(function (Result $result) {
                $result = $result->fetchFirstRow();

                if ($result === null) {
                    throw new UserNotFoundException();
                }

                return User::fromArray($result);
            });
    }

    public function delete(User $user): PromiseInterface
    {
        return $this->connection
            ->delete('users', ['id' => $user->getId()])
            ->then(function (Result $result) {
                if ($result->getAffectedRows() === 0) {
                    throw new UserNotFoundException();
                }

                return true;
            });
    }
}