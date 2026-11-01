<?php

declare(strict_types=1);

namespace UUA\Tests;

use Psr\Container\ContainerInterface;
use UUA\Container\Container;
use UUA\DemoApp\Database\DatabaseMain;
use UUA\DemoApp\Bootstrap;
use PDO;

abstract class DevUnitTestCase extends \PHPUnit\Framework\TestCase
{

    abstract public function testMain(): void;

    protected ContainerInterface $container;

    /**
     * @var array<string, string>
     */
    protected static array $env = [];

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->newContainer(fn(ContainerInterface $c) => $this->mocks($c));
    }

    protected function newContainer(\Closure $mocks): ContainerInterface
    {
        $bootstrap = new class() extends Bootstrap {

            public \Closure $mocks;
            public \Closure $env;

            #[\Override]
            public function newContainer(): ContainerInterface
            {
                return new Container(function (ContainerInterface $c) {
                    $mocks = array_merge($this->assets($c), ($this->mocks)($c));
                    $mocks[DatabaseMain::class] ??= fn() => new DatabaseMain('sqlite::memory:');
                    $mocks['env'] = fn() => array_merge($mocks['env'], ($this->env)());

                    return $mocks;
                });
            }
        };
        $bootstrap->mocks = $mocks;
        $bootstrap->env = fn() => static::$env;

        return $bootstrap->newContainer();
    }

    /**
     * @param ContainerInterface $c
     * @return array<string, mixed>
     */
    protected function mocks(ContainerInterface $c): array
    {
        return [];
    }
}
