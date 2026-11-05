<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Controller;

use UUA\DemoApp\Task\Servant\TaskSaveByPasswordServant;
use UUA\DemoApp\Task\Entity\TaskEntity;

class TaskSaveController extends \UUA\Controller
{

    protected string $password;
    protected TaskEntity $task;

    #[\Override]
    protected function domainUnits(): array
    {
        return [
            TaskSaveByPasswordServant::class,
        ];
    }

    #[\Override]
    protected function viewUnits(): array
    {
        return [
        ];
    }

    #[\Override]
    public function from(object $object): void
    {
        if ($object instanceof TaskSaveByPasswordServant) {
            $this->task = $object->task;
        }
    }

    #[\Override]
    public function to(object $object): void
    {
        if ($object instanceof TaskSaveByPasswordServant) {
            $object->password = $this->password;
        }
    }
}
