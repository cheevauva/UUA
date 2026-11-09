<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\WithContainterInterface;
use Psr\Container\ContainerInterface;

trait CallWithIdTrait
{

    public int $id;

    public static function call(WithContainterInterface|ContainerInterface $container, int $id): static
    {
        $self = static::new($container);
        $self->id = $id;
        $self->serve();

        return $self;
    }
}
