<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\User\Wrapper;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Task\DAO\TaskGetByIdDAO;
use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\DemoApp\User\Entity\UserEntity;
use UUA\DemoApp\User\DAO\UserGetByIdDAO;

class DevUserRetrieveWhenTaskGetByIdWrapperTest extends \UUA\Tests\DevUnitTestCase
{

    public static UserEntity $user;
    public static TaskEntity $task;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $user = self::$user = new UserEntity;
        $user->name = 'TestUser';

        $task = self::$task = new TaskEntity;
        $task->id = 1;
        $task->assignedUserId = null;
    }

    #[\Override]
    public function testMain(): void
    {
        self::$task->assignedUserId = 2;

        $taskById = TaskGetByIdDAO::call($this, 1);

        $user = UserEntity::as($taskById->task->attr('user'));

        self::assertEquals($user->name, 'TestUser');
        self::assertEquals($user->id, 2);
    }

    public function testEmptyAssignedUser(): void
    {
        $taskById = TaskGetByIdDAO::call($this, 1);

        $user = $taskById->task->attr('user');

        self::assertNotInstanceOf(UserEntity::class, $user);
        self::assertEmpty($user);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskGetByIdDAO::class => fn() => new class($c) extends TaskGetByIdDAO {

                #[\Override]
                protected function process(): void
                {
                    $this->task = DevUserRetrieveWhenTaskGetByIdWrapperTest::$task;
                }
            },
            UserGetByIdDAO::class => fn() => new class($c) extends UserGetByIdDAO {

                #[\Override]
                protected function process(): void
                {
                    $this->user = DevUserRetrieveWhenTaskGetByIdWrapperTest::$user;
                    $this->user->id = 2;
                }
            },
        ];
    }
}
