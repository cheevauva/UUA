<?php

declare(strict_types=1);

namespace UUA;

abstract class Wrapper extends Unit
{

    public Unit $rootUnit;
    public \Closure $nextUnit;

    #[\Override]
    public function serve(): void
    {
        $this->onBefore();
        ($this->nextUnit)();
        $this->onAfter();
    }

    abstract protected function onBefore(): void;

    abstract protected function onAfter(): void;
}
