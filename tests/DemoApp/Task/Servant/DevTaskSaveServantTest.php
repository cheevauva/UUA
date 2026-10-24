<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\Servant;

use Psr\Container\ContainerInterface;
use UUA\Tests\Fixtures\DemoApp\Task\DAO\TaskUpdateDAO;
use UUA\Tests\Fixtures\DemoApp\Task\DAO\TaskInsertDAO;
use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;
use UUA\Tests\Fixtures\DemoApp\Task\Servant\TaskSaveServant;

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
        $task->description = 'Description';
        $task->dateCreated = new \DateTimeImmutable('2020-02-02 01:01:01');

        TaskSaveServant::call($this->container, $task);

        self::assertEquals($task, self::$update?->task);
        self::assertNull(self::$insert);
    }

    public function testInsert(): void
    {
        $task = new TaskEntity;
        $task->id = null;
        $task->description = 'Description';
        $task->dateCreated = new \DateTimeImmutable('2020-02-02 01:01:01');

        TaskSaveServant::call($this->container, $task);

        self::assertNull(self::$update);
        self::assertEquals($task, self::$insert?->task);
        self::assertEquals(1, self::$insert?->task->id);
    }

    public function testDateUpdatePassToTask(): void
    {
        $task = new TaskEntity;
        $task->id = null;
        $task->description = 'Description';
        $task->dateCreated = new \DateTimeImmutable('2020-02-02 01:01:01');

        $save = TaskSaveServant::new($this->container);
        $save->task = $task;
        $save->currentTime = new \DateTimeImmutable('2020-02-02 01:02:03');
        $save->serve();

        self::assertEquals(new \DateTimeImmutable('2020-02-02 01:02:03'), $task->dateUpdated);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskUpdateDAO::class => fn() => new class($c) extends TaskUpdateDAO {

                #[\Override]
                public function serve(): void
                {
                    DevTaskSaveServantTest::$update = $this;
                }
            },
            TaskInsertDAO::class => fn() => new class($c) extends TaskInsertDAO {

                #[\Override]
                public function serve(): void
                {
                    DevTaskSaveServantTest::$insert = $this;

                    $this->task->id = 1;
                }
            },
        ];
    }
}
