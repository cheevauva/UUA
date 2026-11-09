<?php

declare(strict_types=1);

namespace UUA;

use Psr\Container\ContainerInterface;

interface WithContainterInterface
{

    public ContainerInterface $container {
        get;
    }
}
