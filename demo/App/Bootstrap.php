<?php

declare(strict_types=1);

namespace UUA\DemoApp\App;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use UUA\DemoApp\Task\DAO\TaskGetByIdDAO;
use UUA\DemoApp\User\Wrapper\UserRetrieveWhenTaskGetByIdWrapper;
use UUA\Servant\EventDispatcherServant;
use UUA\Container\Container;
use UUA\DemoApp\App\Event\WrongPasswordEvent;
use UUA\DemoApp\App\EventListener\LogWrongPasswordEventListener;

class Bootstrap
{

    public function newContainer(): ContainerInterface
    {
        return new Container(fn(ContainerInterface $c) => $this->assets($c));
    }

    /**
     * @param ContainerInterface $c
     * @return array<string, mixed>
     */
    protected function assets(ContainerInterface $c): array
    {
        return [
            'env' => $_ENV,
            EventDispatcherInterface::class => fn() => EventDispatcherServant::singleton($c),
            'wrappers' => [
                TaskGetByIdDAO::class => [
                    'any' => [
                        UserRetrieveWhenTaskGetByIdWrapper::class,
                    ],
                ],
            ],
            'events' => [
                WrongPasswordEvent::class => [
                    LogWrongPasswordEventListener::class,
                ],
            ],
        ];
    }
}
