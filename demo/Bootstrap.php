<?php

declare(strict_types=1);

namespace UUA\DemoApp;

use UUA\DemoApp\Task\DAO\TaskGetByIdDAO;
use UUA\DemoApp\User\Wrapper\UserRetrieveWhenTaskGetByIdWrapper;
use Psr\Container\ContainerInterface;
use UUA\Container\Container;

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
            'wrappers' => [
                TaskGetByIdDAO::class => [
                    'any' => [
                        UserRetrieveWhenTaskGetByIdWrapper::class,
                    ],
                ],
            ],
        ];
    }
}
