<?php

declare(strict_types=1);

namespace UUA\Tests\App\Event;

use UUA\DemoApp\App\Event\CounterEvent;

class DevCounterEventTest extends \UUA\Tests\DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        $event = new CounterEvent();
        $event->dispatch($this->eventDispatcher());

        self::assertEquals(true, $event->isPropagationStopped());
        self::assertEquals(1, $event->counter);
    }
}
