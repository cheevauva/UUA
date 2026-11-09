<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Traits;

use Psr\Container\ContainerInterface;
use UUA\WithContainterInterface;
use UUA\DemoApp\Task\Entity\TaskEntity;

trait TaskCallTrait
{

    public TaskEntity $task;

    public static function call(WithContainterInterface|ContainerInterface $container, TaskEntity $task): self
    {
        $self = self::new($container);
        $self->task = $task;
        $self->serve();

        return $self;
    }
}
