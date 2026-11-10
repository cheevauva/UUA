<?php

declare(strict_types=1);

namespace UUA\Servant;

use UUA\Servant;
use UUA\ContainerConstructInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcherServant extends Servant implements EventDispatcherInterface, ContainerConstructInterface
{

    use \UUA\Traits\FactoryTrait;

    /**
     * @var array<string, array<string>>
     */
    protected array $listeners = [];
    public object $event;

    #[\Override]
    protected function init(): void
    {
        /** @var array<string, array<int, class-string>> $events */
        $events = $this->container->get('events');

        foreach ($events as $event => $eventListeners) {
            foreach ($eventListeners as $priority => $eventListener) {
                $this->listeners[$event][999999 - $priority] = $eventListener;
            }
        }
    }

    #[\Override]
    public function dispatch(object $event): object
    {
        $this->event = $event;
        $this->serve();

        return $event;
    }

    #[\Override]
    protected function run(): void
    {
        $event = $this->event;

        $listeners = $this->listeners[$event::class] ?? [];

        foreach ($listeners as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $unit = $this->factory()->make($listener);

            if (is_callable($unit)) {
                $unit($event);
            }
        }
    }
}
