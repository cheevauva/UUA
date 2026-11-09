<?php

declare(strict_types=1);

namespace UUA\Traits;

use Psr\Container\ContainerInterface;

trait ContainerTrait
{

    public protected(set) ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->init();
    }

    protected function init(): void
    {
        
    }
}
