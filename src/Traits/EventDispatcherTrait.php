<?php

declare(strict_types=1);

namespace UUA\Traits;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherTrait
{

    use ContainerTrait;

    protected function eventDispatcher(): EventDispatcherInterface
    {
        // @phpstan-ignore-next-line
        return $this->container->get(EventDispatcherInterface::class);
    }
    
}
