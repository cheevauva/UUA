<?php

declare(strict_types=1);

namespace UUA\Traits;


use UUA\Servant\WrapAndServeServant;
use UUA\Unit;
use UUA\Wrapper;

trait WrapAndServeTrait
{

    use ContainerTrait;

    public function wrapAndServe(object $caller): void
    {
        $wrapAndServe = WrapAndServeServant::singleton($this->container);
        $wrapAndServe->caller = $caller;
        $wrapAndServe->unit = $this;
        $wrapAndServe->serve();
    }
}
