<?php

declare(strict_types=1);

namespace UUA\Tests\Traits;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Database\DatabaseMain;
use UUA\FactoryInterface;
use UUA\Exception\ClassMismatchException;

class DevBuildTraitTest extends \UUA\Tests\DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        self::assertNotSame(DatabaseMain::new($this->container), DatabaseMain::new($this->container));
        self::assertSame(DatabaseMain::singleton($this->container), DatabaseMain::singleton($this->container));
    }

    public function testFactoryNotImplementedFactoryInterfaceForNew(): void
    {
        $this->expectException(ClassMismatchException::class);
        $this->expectExceptionMessageIs('Expected dependency object for UUA\FactoryInterface, received stdClass');

        $container = $this->newContainer(function (ContainerInterface $c) {
            return [
                FactoryInterface::class => new \stdClass(),
            ];
        });

        DatabaseMain::new($container);
    }

    public function testFactoryNotImplementedFactoryInterfaceForSingleton(): void
    {
        $this->expectException(ClassMismatchException::class);
        $this->expectExceptionMessageIs('Expected UUA\DemoApp\Database\DatabaseMain, but received stdClass');

        $container = $this->newContainer(function (ContainerInterface $c) {
            return [
                DatabaseMain::class => fn($c) => new \stdClass(),
            ];
        });

        DatabaseMain::singleton($container);
    }
}
