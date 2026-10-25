<?php

declare(strict_types=1);

namespace UUA\Traits;

use Psr\Container\ContainerInterface;
use UUA\FactoryInterface;
use UUA\Exception\ClassMismatchException;

trait BuildTrait
{

    use AsTrait;

    final public static function new(ContainerInterface $container): static
    {
        $factory = $container->get(FactoryInterface::class);

        if ($factory instanceof FactoryInterface) {
            return static::as($factory->make(static::class));
        }

        throw new ClassMismatchException(sprintf('Expected dependency object for %s, received %s', FactoryInterface::class, get_debug_type($factory)));
    }

    public static function singleton(ContainerInterface $container): static
    {
        $singleton = $container->get(static::class);

        if ($singleton instanceof static) {
            return static::as($singleton);
        }

        throw new ClassMismatchException(sprintf('Expected %s, but received %s', static::class, get_debug_type($singleton)));
    }
}
