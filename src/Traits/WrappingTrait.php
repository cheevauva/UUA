<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\UnitInterface;
use UUA\Wrapper;

trait WrappingTrait
{

    use ContainerTrait;

    protected function wrapping(UnitInterface $unit): UnitInterface
    {
        $wrappers = $this->container->get('wrappers')[$unit::class] ?? [];

        $prev = $unit;

        foreach ($wrappers as $wrapper) {
            $next = Wrapper::as($wrapper::new($this->container));
            $next->rootUnit = $unit;
            $next->nextUnit = $prev;

            $prev = $next;
        }

        return $next ?? $unit;
    }
}
