<?php

declare(strict_types=1);

namespace UUA\Tests;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use UUA\DemoApp\App\Database\DatabaseMain;
use UUA\DemoApp\App\Bootstrap;
use UUA\DemoApp\App\Servant\FileLoggerServant;
use UUA\WithContainterInterface;

abstract class DevUnitTestCase extends \PHPUnit\Framework\TestCase implements WithContainterInterface
{

    abstract public function testMain(): void;

    public protected(set) ContainerInterface $container;

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

            /**
             * @param ContainerInterface $c
             * @return array<string, mixed>
             */
            #[\Override]
            protected function assets(ContainerInterface $c): array
            {
                $mocks = array_merge(parent::assets($c), ($this->mocks)($c));
                $mocks[DatabaseMain::class] ??= fn() => new DatabaseMain('sqlite::memory:');
                $mocks[FileLoggerServant::class] ??= fn() => new class($c) extends FileLoggerServant {

                    public string $filename = '/dev/null';
                };
                $mocks['env'] = fn() => array_merge($mocks['env'], ($this->env)());

                return $mocks;
            }
        };
        $bootstrap->mocks = $mocks;
        $bootstrap->env = fn() => static::$env;

        return $bootstrap->newContainer();
    }

    protected function eventDispatcher(): EventDispatcherInterface
    {
        // @phpstan-ignore-next-line
        return $this->container->get(EventDispatcherInterface::class);
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
