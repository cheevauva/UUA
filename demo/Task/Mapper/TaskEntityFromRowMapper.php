<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Mapper;

use UUA\DemoApp\Task\Entity\TaskEntity;

class TaskEntityFromRowMapper extends \UUA\Mapper
{

    use \UUA\DemoApp\Traits\MapperFromRowTrait;

    public TaskEntity $task;

    #[\Override]
    public function serve(): void
    {
        $task = $this->task = new TaskEntity;
        $task->id = $this->int('id');
        $task->description = $this->string('description');
        $task->dateCreated = $this->dateTime('date_created');
        $task->dateUpdated = $this->dateTime('date_updated');
    }
}
