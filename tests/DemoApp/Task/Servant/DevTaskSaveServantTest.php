<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\Servant;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Task\DAO\TaskUpdateDAO;
use UUA\DemoApp\Task\DAO\TaskInsertDAO;
use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\DemoApp\Task\Servant\TaskSaveServant;

class DevTaskSaveServantTest extends \UUA\Tests\DevUnitTestCase
{

    public static ?TaskUpdateDAO $update;
    public static ?TaskInsertDAO $insert;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        self::$insert = null;
        self::$update = null;
    }

    #[\Override]
    public function testMain(): void
    {
        $task = new TaskEntity;
        $task->id = 1;

        TaskSaveServant::call($this->container, $task);

        self::assertEquals($task, self::$update?->task);
        self::assertNull(self::$insert);
    }

    public function testInsert(): void
    {
        $task = new TaskEntity;
        $task->id = null;

        TaskSaveServant::call($this->container, $task);

        self::assertNull(self::$update);
        self::assertEquals($task, self::$insert?->task);
        self::assertEquals(1, self::$insert?->task->id);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskUpdateDAO::class => fn() => new class($c) extends TaskUpdateDAO {

                #[\Override]
                protected function run(): void
                {
                    DevTaskSaveServantTest::$update = $this;
                }
            },
            TaskInsertDAO::class => fn() => new class($c) extends TaskInsertDAO {

                #[\Override]
                protected function run(): void
                {
                    DevTaskSaveServantTest::$insert = $this;

                    $this->task->id = 1;
                }
            },
        ];
    }
}
