<?php

declare(strict_types=1);

namespace App\Controllers;

use Domain\Exceptions\UserNotFoundException;
use Domain\Models\User;
use Domain\Queries\GetUserQuery;
use Drift\CommandBus\Bus\QueryBus;
use React\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserController
{

    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @return PromiseInterface<Response>
     */
    public function __invoke(string $id)
    {
        return $this->queryBus

            ->ask(new GetUserQuery((int)$id))

            ->then( function (User $user) {

                return new JsonResponse($user->toArray());

            })

            ->otherwise(function (UserNotFoundException $e) use ($id) {
                return new JsonResponse([
                    'message' => "User {$id} not found"
                ], 404);
            });
        
    }
}
