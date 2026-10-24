<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Task\Traits;

use Psr\Container\ContainerInterface;
use UUA\Tests\Fixtures\DemoApp\Task\Entity\TaskEntity;

trait TaskCallTrait
{

    public TaskEntity $task;

    public static function call(ContainerInterface $container, TaskEntity $task): self
    {
        $self = self::new($container);
        $self->task = $task;
        $self->serve();

        return $self;
    }
}
