<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\Mapper;

use UUA\DemoApp\Task\Mapper\TaskEntityFromRowMapper;
use UUA\DemoApp\Task\Entity\TaskEntity;

class DevTaskEntityFromRowMapperTest extends \UUA\Tests\DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        $task = new TaskEntity;
        $task->id = 1;
        $task->description = 'Description';
        $task->dateCreated = new \DateTimeImmutable('2020-02-02 01:01:01');
        $task->dateUpdated = new \DateTimeImmutable('2020-02-02 02:02:02');

        self::assertEquals($task, TaskEntityFromRowMapper::call($this->container, [
            'id' => 1,
            'description' => 'Description',
            'date_created' => '2020-02-02 01:01:01',
            'date_updated' => '2020-02-02 02:02:02',
        ])->task);
    }
}
