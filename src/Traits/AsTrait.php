<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\Exception\ClassMismatchException;

trait AsTrait
{

    public static function as(mixed $self): static
    {
        if ($self instanceof static) {
            return $self;
        }
        
        $message = sprintf('Expected %s, but received %s', static::class, get_debug_type($self));
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        if (isset($backtrace[0]['file'], $backtrace[0]['line'])) {
            $message = sprintf('%s. Called in %s (%s)', $message, $backtrace[0]['file'], $backtrace[0]['line']);
        }

        throw new ClassMismatchException($message);
    }
}
