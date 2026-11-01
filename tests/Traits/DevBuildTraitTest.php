<?php

declare(strict_types=1);

namespace UUA\Tests\Traits;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Database\DatabaseMain;
use UUA\FactoryInterface;
use UUA\Exception\ClassMismatchException;
use UUA\Servant\WrapAndServeServant;
use UUA\Servant;

class DevBuildTraitTest extends \UUA\Tests\DevUnitTestCase
{

    public static int $wrapAndServeInitCalls;

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

    public function testInitCallOnce(): void
    {
        self::$wrapAndServeInitCalls = 0;
        
        $container = $this->newContainer(function (ContainerInterface $c) {
            return [
                WrapAndServeServant::class => fn() => new class($c) extends WrapAndServeServant {

                    #[\Override]
                    protected function init(): void
                    {
                        parent::init();

                        DevBuildTraitTest::$wrapAndServeInitCalls++;
                    }
                },
            ];
        });

        $unit = new class($container) extends Servant {

            #[\Override]
            public function serve(): void
            {
                
            }
        };

        for ($x = 0; $x <= 2; $x++) {
            $wrapAndServe = WrapAndServeServant::singleton($container);
            $wrapAndServe->unit = $unit;
            $wrapAndServe->caller = $this;
            $wrapAndServe->serve();
        }

        self::assertEquals(self::$wrapAndServeInitCalls, 1);
    }
}
