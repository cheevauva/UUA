<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Task\Servant;

use UUA\Tests\Fixtures\DemoApp\Task\DAO\TaskUpdateDAO;
use UUA\Tests\Fixtures\DemoApp\Task\DAO\TaskInsertDAO;

class TaskSaveServant extends \UUA\Servant
{

    use \UUA\Tests\Fixtures\DemoApp\Task\Traits\TaskCallTrait;
    use \UUA\Traits\CurrentTimeTrait;

    #[\Override]
    public function serve(): void
    {
        $this->task->dateCreated ??= $this->currentTime();
        $this->task->dateUpdated = $this->currentTime();

        if (isset($this->task->id)) {
            TaskUpdateDAO::call($this->container, $this->task);
        } else {
            TaskInsertDAO::call($this->container, $this->task);
        }
    }
}
