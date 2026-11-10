<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\DAO;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\DemoApp\Task\Mapper\TaskEntityFromRowMapper;
use UUA\DemoApp\Task\Exception\TaskEntityNotFoundException;
use UUA\DemoApp\Task\DAO\TaskGetByIdDAO;

class DevTaskGetByIdDAOTest extends \UUA\Tests\DevUnitTestCase
{

    /**
     * @var null|array<string, mixed>
     */
    public static ?array $params;
    public static ?string $sql;
    public static ?TaskEntityFromRowMapper $mapper;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        self::$sql = null;
        self::$params = null;
        self::$mapper = null;
    }

    #[\Override]
    public function testMain(): void
    {
        $task = TaskGetByIdDAO::call($this, 1)->task;

        self::assertEquals('SELECT * FROM tasks WHERE id = :id', self::$sql);
        self::assertEquals([
            'id' => 1,
        ], self::$params);
        self::assertEquals(1, $task->id);
    }

    public function testNotFound(): void
    {
        $this->expectException(TaskEntityNotFoundException::class);
        
        TaskGetByIdDAO::call($this, 2)->task;
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskGetByIdDAO::class => fn() => new class($c) extends TaskGetByIdDAO {

                #[\Override]
                protected function getSQL(): string
                {
                    DevTaskGetByIdDAOTest::$sql = parent::getSQL();

                    if ($this->id === 2) {
                        return 'SELECT 1 WHERE 1 = 0';
                    }
                    
                    return 'SELECT 1 AS id';
                }

                #[\Override]
                protected function getParams(): array
                {
                    DevTaskGetByIdDAOTest::$params = parent::getParams();

                    return [];
                }
            },
            TaskEntityFromRowMapper::class => fn() => new class($c) extends TaskEntityFromRowMapper {

                #[\Override]
                protected function run(): void
                {
                    DevTaskGetByIdDAOTest::$mapper = $this;

                    $task = $this->task = new TaskEntity;
                    $task->id = $this->int('id');
                }
            },
        ];
    }
}
