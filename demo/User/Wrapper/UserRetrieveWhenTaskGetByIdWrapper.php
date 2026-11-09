<?php

declare(strict_types=1);

namespace UUA\DemoApp\User\Wrapper;

use UUA\DemoApp\Task\DAO\TaskGetByIdDAO;
use UUA\DemoApp\User\DAO\UserGetByIdDAO;

class UserRetrieveWhenTaskGetByIdWrapper extends \UUA\Wrapper
{

    #[\Override]
    protected function onBefore(): void
    {
        
    }

    #[\Override]
    protected function onAfter(): void
    {
        $task = TaskGetByIdDAO::as($this->rootUnit)->task;

        if ($task->assignedUserId) {
            $task->withAttr('user', UserGetByIdDAO::call($this, $task->assignedUserId)->user);
        }
    }
}
