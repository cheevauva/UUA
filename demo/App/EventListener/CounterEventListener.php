<?php

declare(strict_types=1);

namespace UUA\DemoApp\App\EventListener;

use UUA\DemoApp\App\Event\CounterEvent;

class CounterEventListener extends \UUA\EventListener
{

    #[\Override]
    public function __invoke(object $event): void
    {
        if ($event instanceof CounterEvent) {
            $event->isHandled = true;
            $event->counter++;
        }
    }
}
