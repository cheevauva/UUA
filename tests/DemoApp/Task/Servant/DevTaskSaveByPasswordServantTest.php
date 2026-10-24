<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\Servant;

use Psr\Container\ContainerInterface;
use UUA\Tests\Fixtures\DemoApp\Task\Servant\TaskSaveByPasswordServant;
use UUA\Tests\Fixtures\DemoApp\Task\Servant\TaskSaveServant;
use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;
use UUA\Tests\Fixtures\DemoApp\Task\Exception\TaskSaveWrongPasswordException;

class DevTaskSaveByPasswordServantTest extends \UUA\Tests\DevUnitTestCase
{

    public static ?TaskSaveServant $save;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        self::$save = null;
    }

    #[\Override]
    public function testMain(): void
    {
        self::$env['DEMO_APP_PASSWORD'] = 'password';

        $task = new TaskEntity;
        $task->id = null;
        $task->description = 'Description';

        $saveByPassword = TaskSaveByPasswordServant::new($this->container);
        $saveByPassword->task = $task;
        $saveByPassword->password = 'password';
        $saveByPassword->serve();

        self::assertNotNull(self::$save);
    }

    public function testWrongPassword(): void
    {
        $this->expectException(TaskSaveWrongPasswordException::class);

        self::$env['DEMO_APP_PASSWORD'] = 'password';

        $task = new TaskEntity;
        $task->id = null;
        $task->description = 'Description';

        $saveByPassword = TaskSaveByPasswordServant::new($this->container);
        $saveByPassword->task = $task;
        $saveByPassword->password = 'password1';
        $saveByPassword->serve();
    }

    public function testEnvPasswordIsUndefined(): void
    {
        $this->expectExceptionMessageIs('Ключ DEMO_APP_PASSWORD не найден в массиве');

        self::$env = [];

        $task = new TaskEntity;
        $task->id = null;
        $task->description = 'Description';

        $saveByPassword = TaskSaveByPasswordServant::new($this->container);
        $saveByPassword->task = $task;
        $saveByPassword->password = 'password1';
        $saveByPassword->serve();
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskSaveServant::class => fn() => new class($c) extends TaskSaveServant {

                #[\Override]
                public function serve(): void
                {
                    DevTaskSaveByPasswordServantTest::$save = $this;
                }
            },
        ];
    }
}
