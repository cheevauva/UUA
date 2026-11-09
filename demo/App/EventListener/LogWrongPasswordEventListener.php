<?php

declare(strict_types=1);

namespace UUA\DemoApp\App\EventListener;

use UUA\DemoApp\App\Event\WrongPasswordEvent;
use UUA\DemoApp\App\Servant\FileLoggerServant;

class LogWrongPasswordEventListener extends \UUA\EventListener
{
    #[\Override]
    public function __invoke(object $event): void
    {
        if ($event instanceof WrongPasswordEvent) {
            $logger = FileLoggerServant::new($this);
            $logger->message = sprintf('Пользователь указан некорректный пароль - "%s"', $event->wrongPassword);
            $logger->serve();
        }
    }
}
