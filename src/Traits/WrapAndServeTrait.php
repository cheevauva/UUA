<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\Servant\WrapAndServeServant;
use UUA\Unit;

trait WrapAndServeTrait
{

    use ContainerTrait;

    protected function wrapAndServe(Unit $unit): void
    {
        $wrapAndServe = WrapAndServeServant::singleton($this->container);
        $wrapAndServe->caller = $this;
        $wrapAndServe->unit = $unit;
        $wrapAndServe->serve();
    }
}
