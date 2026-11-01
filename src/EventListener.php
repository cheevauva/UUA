<?php

declare(strict_types=1);

namespace UUA;

abstract class EventListener implements ContainerConstructInterface
{

    use Traits\ContainerTrait;
    use Traits\BuildTrait;

    abstract public function __invoke(object $event): void;
}
