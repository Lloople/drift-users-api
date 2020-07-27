<?php

namespace Domain\Middlewares;

use Domain\Commands\UpdateUserCommand;
use Drift\CommandBus\Middleware\DiscriminableMiddleware;
use Exception;

use function React\Promise\reject;

class CheckUserNameLengthMiddleware implements DiscriminableMiddleware
{

    const MIN_NAME_LENGTH = 5;

    public function execute($command, callable $next)
    {
        if (strlen($command->getUser()->getName()) < self::MIN_NAME_LENGTH) {

            return reject(new Exception("User name should be at least " . self::MIN_NAME_LENGTH));

        }

        return $next($command);
    }

    public function onlyHandle(): array
    {
        return [
            UpdateUserCommand::class
        ];
    }
}