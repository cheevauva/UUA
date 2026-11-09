<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\Unit;
use UUA\Wrapper;

trait WrappingTrait
{

    use ContainerTrait;

    #[\Override]
    public function serve(): void
    {
        $unitName = str_contains(get_debug_type($this), '@anonymous') ? get_parent_class($this) : $this::class;

        $prev = $this->process(...);

        /** @var array<string, array<int, string>> $wrappers */
        $wrappers = $this->container->get('wrappers');

        foreach ($wrappers[$unitName] ?? [] as $wrapper) {
            $next = Wrapper::as($wrapper::new($this->container));
            $next->rootUnit = $this;
            $next->nextUnit = $prev;

            $prev = $next->serve(...);
        }

        if (empty($next)) {
            $this->process();
            return;
        }

        Unit::as($next)->serve();
    }

    abstract protected function process(): void;
}
