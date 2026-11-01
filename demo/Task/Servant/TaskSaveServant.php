<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Servant;

use UUA\DemoApp\Task\DAO\TaskUpdateDAO;
use UUA\DemoApp\Task\DAO\TaskInsertDAO;

class TaskSaveServant extends \UUA\Servant
{

    use \UUA\DemoApp\Task\Traits\TaskCallTrait;

    #[\Override]
    public function serve(): void
    {
        if (isset($this->task->id)) {
            TaskUpdateDAO::call($this->container, $this->task);
        } else {
            TaskInsertDAO::call($this->container, $this->task);
        }
    }
}
