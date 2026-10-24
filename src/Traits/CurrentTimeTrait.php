<?php

declare(strict_types=1);

namespace UUA\Traits;

trait CurrentTimeTrait
{

    public \DateTimeImmutable $currentTime;

    protected function currentTime(): \DateTimeImmutable
    {
        return $this->currentTime ??= new \DateTimeImmutable;
    }
}
