<?php

namespace Tests\Domain\Repositories;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Domain\Repositories\DBALUserRepository;
use Domain\Repositories\UserRepository;
use Drift\DBAL\Connection;
use Drift\DBAL\Credentials;
use Drift\DBAL\Driver\SQLite\SQLiteDriver;

class DBALUserRepositoryTest extends UserRepositoryTest
{
    protected function createRepository(array $users = []): UserRepository
    {
        $connection = $this->createConnection();

        $this->migrate($connection);

        foreach ($users as $id => $user) {
            $connection->insert('users', $user->toArray());
        }

        return new DBALUserRepository($connection);
    }

    protected function createConnection(): Connection
    {
        return Connection::createConnected(
            new SQLiteDriver($this->loop),
            new Credentials('', '', 'root', 'root', ':memory:'),
            new SqlitePlatform()
        );
    }

    protected function migrate(Connection $connection): void
    {
        $connection->createTable('users', [
            'id' => 'integer',
            'name' => 'string'
        ]);
    }
}