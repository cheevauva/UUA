<?php

declare(strict_types=1);

namespace UUA\DemoApp\App\Event;

class WrongPasswordEvent extends \UUA\Event
{

    public string $wrongPassword;
}
