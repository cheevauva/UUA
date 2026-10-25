<?php

declare(strict_types=1);

namespace UUA\Tests;

use Psr\Container\ContainerInterface;
use UUA\Container\Container;
use UUA\DemoApp\Database\DatabaseMain;
use PDO;

abstract class DevUnitTestCase extends \PHPUnit\Framework\TestCase
{

    abstract public function testMain(): void;

    protected ContainerInterface $container;
    /**
     * @var array<string, string>
     */
    protected static array $env;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->newContainer(function (ContainerInterface $c) {
            $mocks = $this->mocks($c);
            $mocks[DatabaseMain::class] = fn() => new PDO('sqlite::memory:');
            $mocks['env'] = fn() => self::$env;
            
            return $mocks;
        });
    }

    protected function newContainer(\Closure $mocks): ContainerInterface
    {
        return new Container($mocks);
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
