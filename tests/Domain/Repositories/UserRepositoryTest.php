<?php

namespace Tests\Domain\Repositories;

use Domain\Exceptions\UserNotFoundException;
use Domain\Models\User;
use Domain\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;

use function Clue\React\Block\await;

abstract class UserRepositoryTest extends TestCase
{
    protected $loop;

    public function setUp(): void
    {
        $this->loop = Factory::create();
    }

    public function tearDown(): void
    {
        $this->loop = null;
    }

    /** @test */
    public function user_not_found_throws_an_exception()
    {
        $this->expectException(UserNotFoundException::class);

        await($this->createRepository()->find(123), $this->loop);
    }

    /** @test */
    public function user_can_be_found()
    {
        $user = await($this->createRepository([4 => new User(4, 'Han Solo')])->find(4), $this->loop);

        $this->assertEquals(4, $user->getId());
        $this->assertEquals('Han Solo', $user->getName());
    }

    /** @test */
    public function user_not_deleted_throws_an_exception()
    {
        $this->expectException(UserNotFoundException::class);

        await($this->createRepository([4 => new User(4, 'Han Solo')])->delete(new User(8, 'Luke Skywalker')), $this->loop);
    }

    /** @test */
    public function user_can_be_deleted()
    {
        $user = new User(4, 'Han Solo');

        $this->assertTrue(
            await($this->createRepository([$user->getId() => $user])->delete($user), $this->loop)
        );
    }

    /** @test */
    public function user_can_be_saved()
    {
        $repository = $this->createRepository();

        await($repository->save(new User(1, 'Han Solo')), $this->loop);

        $this->assertEquals('Han Solo', await($repository->find(1), $this->loop)->getName());
    }

    /** @test */
    public function user_saved_twice_overrides_properties()
    {
        $repository = $this->createRepository([4 => new User(4, 'Han Solo')]);
        
        await($repository->save(new User(4, 'Chewbacca')), $this->loop);

        $this->assertEquals('Chewbacca', await($repository->find(4), $this->loop)->getName());
    }

    abstract protected function createRepository(array $users = []): UserRepository;

}