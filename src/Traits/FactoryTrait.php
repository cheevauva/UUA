<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\FactoryInterface;

trait FactoryTrait
{

    use ContainerTrait;

    protected function factory(): FactoryInterface
    {
        // @phpstan-ignore-next-line
        return $this->container->get(FactoryInterface::class);
    }
}
