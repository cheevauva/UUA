<?php

declare(strict_types=1);

namespace UUA\DemoApp\App\Event;

use Psr\EventDispatcher\StoppableEventInterface;

class CounterEvent extends \UUA\Event implements StoppableEventInterface
{

    public bool $isHandled = false;
    public int $counter = 0;

    #[\Override]
    public function isPropagationStopped(): bool
    {
        return $this->isHandled;
    }
}
