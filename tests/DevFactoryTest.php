<?php

declare(strict_types=1);

namespace UUA\Tests;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Database\DatabaseMain;
use UUA\FactoryInterface;
use UUA\Exception\DuplicateInstanceException;
use UUA\Exception\InvalidFactoryResultException;

class DevFactoryTest extends DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        self::assertNotSame($this->factory()->make(DatabaseMain::class), $this->factory()->make(DatabaseMain::class));
        self::assertInstanceOf(DatabaseMain::class, $this->factory()->make(DatabaseMain::class));
    }

    public function testInstanceAlreadyExists(): void
    {
        $this->expectException(DuplicateInstanceException::class);
        $this->expectExceptionMessageIs('Instance for "abc" already exists. Use a Closure for lazy loading');

        $this->factory()->make('abc');
    }

    public function testReturnedNotObject(): void
    {
        $this->expectException(InvalidFactoryResultException::class);
        $this->expectExceptionMessageIs('Factory closure for "def" did not return an object.');

        $this->factory()->make('def');
    }

    protected function factory(): FactoryInterface
    {
        // @phpstan-ignore-next-line
        return $this->container->get(FactoryInterface::class);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            'abc' => new DatabaseMain('sqlite::memory:'),
            'def' => fn() => '111',
        ];
    }
}
