<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Task\Servant;

use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;
use UUA\Tests\Fixtures\DemoApp\Task\Servant\TaskSaveServant;
use UUA\Tests\Fixtures\DemoApp\Task\Exception\TaskSaveWrongPasswordException;

class TaskSaveByPasswordServant extends \UUA\Servant
{

    use \UUA\Traits\EnvTrait;

    public string $password;
    public TaskEntity $task;

    #[\Override]
    public function serve(): void
    {
        if ($this->password !== $this->env()['DEMO_APP_PASSWORD']) {
            throw new TaskSaveWrongPasswordException();
        }
        
        TaskSaveServant::call($this->container, $this->task);
    }
}
