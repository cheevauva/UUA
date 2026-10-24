<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\Task\Mapper;

use UUA\Tests\Fixtures\DemoApp\Task\Mapper\TaskEntityToRowMapper;
use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;

class DevTaskEntityToRowMapperTest extends \UUA\Tests\DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        $task = new TaskEntity;
        $task->id = 1;
        $task->description = 'Description';
        $task->dateCreated = new \DateTimeImmutable('2020-02-02 01:01:01');
        $task->dateUpdated = new \DateTimeImmutable('2020-02-02 02:02:02');

        self::assertEquals([
            'id' => 1,
            'description' => 'Description',
            'date_created' => '2020-02-02 01:01:01',
            'date_updated' => '2020-02-02 02:02:02',
        ], TaskEntityToRowMapper::call($this->container, $task)->row);
    }
}
