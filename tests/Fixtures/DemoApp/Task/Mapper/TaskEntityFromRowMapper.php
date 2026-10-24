<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Task\Mapper;

use Psr\Container\ContainerInterface;
use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;

class TaskEntityFromRowMapper extends \UUA\Mapper
{

    public TaskEntity $task;

    /**
     * @var array<string, mixed>
     */
    public array $row;

    #[\Override]
    public function serve(): void
    {
        $task = $this->task = new TaskEntity;
        $task->id = intval($this->row['id'] ?? throw new \Exception('row.id is undefined'));
        $task->description = strval($this->row['description'] ?? throw new \Exception('row.description is undefined'));
        $task->dateCreated = new \DateTimeImmutable($this->row['date_created'] ?? throw new \Exception('row.date_created is undefined'));
        $task->dateUpdated = new \DateTimeImmutable($this->row['date_updated'] ?? throw new \Exception('row.date_updated is undefined'));
    }

    /**
     * @param ContainerInterface $container
     * @param array<string, mixed> $row
     * @return self
     */
    public static function call(ContainerInterface $container, array $row): self
    {
        $self = self::new($container);
        $self->row = $row;
        $self->serve();

        return $self;
    }
}
