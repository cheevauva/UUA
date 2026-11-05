<?php

declare(strict_types=1);

namespace UUA;

interface ObjectInteractionInterface
{

    public function to(object $object): void;

    public function from(object $object): void;
}
