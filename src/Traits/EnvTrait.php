<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\ArrayObjectStrict;

trait EnvTrait
{

    use ContainerTrait;

    protected function env(): ArrayObjectStrict
    {
        // @phpstan-ignore-next-line
        return new ArrayObjectStrict($this->container->get('env'));
    }
}
