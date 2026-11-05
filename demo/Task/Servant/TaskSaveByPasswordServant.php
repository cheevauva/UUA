<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Servant;

use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\DemoApp\Task\Servant\TaskSaveServant;
use UUA\DemoApp\Task\Exception\TaskSaveWrongPasswordException;
use UUA\DemoApp\App\Event\WrongPasswordEvent;
use UUA\DemoApp\App\Event\CounterEvent;

class TaskSaveByPasswordServant extends \UUA\Servant
{

    use \UUA\Traits\EnvTrait;

    public string $password;
    public TaskEntity $task;

    #[\Override]
    public function serve(): void
    {
        (new CounterEvent())->dispatch($this->eventDispatcher());

        try {
            $this->process();
        } catch (TaskSaveWrongPasswordException $ex) {
            $event = new WrongPasswordEvent();
            $event->wrongPassword = $this->password;
            $event->dispatch($this->eventDispatcher());

            throw $ex;
        }

        TaskSaveServant::call($this->container, $this->task);
    }

    protected function process(): void
    {
        if ($this->password !== $this->env()['DEMO_APP_PASSWORD']) {
            throw new TaskSaveWrongPasswordException();
        }

        TaskSaveServant::call($this->container, $this->task);
    }
}
