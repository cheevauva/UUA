<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\DAO;

use Psr\Container\ContainerInterface;
use UUA\Tests\Fixtures\DemoApp\Task\DAO\TaskUpdateDAO;
use UUA\Tests\Fixtures\DemoApp\Task\Mapper\TaskEntityToRowMapper;
use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;

class DevTaskUpdateDAOTest extends \UUA\Tests\DevUnitTestCase
{

    /**
     * @var null|array<string,mixed>
     */
    public static ?array $params;
    public static ?string $sql;
    public static ?TaskEntityToRowMapper $mapper;

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
        $task = new TaskEntity;
        $task->id = 1;
        $task->description = 'new description';
        $task->dateCreated = new \DateTimeImmutable('2020-02-02 02:02:02');

        TaskUpdateDAO::call($this->container, $task);

        self::assertEquals('UPDATE tasks SET id = :id, description = :description WHERE id = :id', self::$sql);
        self::assertEquals([
            'id' => 1,
            'description' => 'desc'
        ], self::$params);
        self::assertEquals($task, self::$mapper?->task);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            TaskUpdateDAO::class => fn() => new class($c) extends TaskUpdateDAO {

                #[\Override]
                protected function getSQL(): string
                {
                    DevTaskUpdateDAOTest::$sql = parent::getSQL();

                    return 'SELECT 1';
                }

                #[\Override]
                protected function getParams(): array
                {
                    DevTaskUpdateDAOTest::$params = parent::getParams();

                    return [];
                }
            },
            TaskEntityToRowMapper::class => fn() => new class($c) extends TaskEntityToRowMapper {

                #[\Override]
                public function serve(): void
                {
                    DevTaskUpdateDAOTest::$mapper = $this;

                    $this->row = [
                        'id' => 1,
                        'description' => 'desc'
                    ];
                }
            },
        ];
    }
}
