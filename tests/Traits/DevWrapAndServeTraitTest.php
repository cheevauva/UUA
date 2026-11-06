<?php

declare(strict_types=1);

namespace UUA\Tests\Traits;

use Psr\Container\ContainerInterface;
use UUA\Servant;
use UUA\Traits\WrapAndServeTrait;
use UUA\DemoApp\Task\DAO\TaskGetByIdDAO;
use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\Servant\WrapAndServeServant;

class DevWrapAndServeTraitTest extends \UUA\Tests\DevUnitTestCase
{

    public static TaskEntity $task;
    public static ?object $caller;
    public static ?TaskGetByIdDAO $unit;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        self::$task = new TaskEntity;
        self::$caller = null;
        self::$unit = null;
    }

    #[\Override]
    public function testMain(): void
    {
        $unit = new class($this->container) extends Servant {

            use WrapAndServeTrait;

            #[\Override]
            public function serve(): void
            {
                $taskById = TaskGetByIdDAO::new($this->container);
                $taskById->id = 1;

                $this->wrapAndServe($taskById);
            }
        };
        $unit->serve();

        self::assertNotEmpty(self::$unit);
        self::assertNotEmpty(self::$caller);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskGetByIdDAO::class => fn() => new class($c) extends TaskGetByIdDAO {

                #[\Override]
                public function serve(): void
                {
                    $this->task = DevWrapAndServeTraitTest::$task;
                }
            },
            WrapAndServeServant::class => fn() => new class($c) extends WrapAndServeServant {

                #[\Override]
                public function serve(): void
                {
                    DevWrapAndServeTraitTest::$caller = $this->caller;
                    DevWrapAndServeTraitTest::$unit = $this->unit;
                }
            },
        ];
    }
}
