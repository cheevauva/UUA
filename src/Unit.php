<?php

declare(strict_types=1);

namespace UUA;

abstract class Unit implements UnitInterface, WithContainterInterface
{

    use Traits\AsTrait;
    use Traits\ContainerTrait;
    use Traits\BuildTrait;

    #[\Override]
    public function serve(): void
    {
        $this->run();
    }

    abstract protected function run(): void;
}
