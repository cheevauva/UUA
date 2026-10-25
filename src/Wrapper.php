<?php

declare(strict_types=1);

namespace UUA;

abstract class Wrapper extends Unit
{

    use Traits\ContainerTrait;
    use Traits\BuildTrait;

    public UnitInterface $rootUnit;
    public UnitInterface $nextUnit;

    #[\Override]
    public function serve(): void
    {
        $this->onBefore();
        $this->nextUnit->serve();
        $this->onAfter();
    }

    protected function onBefore(): void
    {
        
    }

    protected function onAfter(): void
    {
        
    }
}
