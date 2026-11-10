<?php

declare(strict_types=1);

namespace UUA\Tests\App\Event;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\App\Event\WrongPasswordEvent;
use UUA\DemoApp\App\Servant\FileLoggerServant;

class DevWrongPasswordEventTest extends \UUA\Tests\DevUnitTestCase
{

    public static ?string $message;

    /**
     * @var array<mixed, mixed>|null
     */
    public static ?array $context;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        self::$context = null;
        self::$message = null;
    }

    #[\Override]
    public function testMain(): void
    {
        $event = new WrongPasswordEvent();
        $event->wrongPassword = 'password';
        $event->dispatch($this->eventDispatcher());

        self::assertEquals('Пользователь указан некорректный пароль - "password"', self::$message);
        self::assertEquals([], self::$context);
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            FileLoggerServant::class => fn() => new class($c) extends FileLoggerServant {

                #[\Override]
                protected function run(): void
                {
                    DevWrongPasswordEventTest::$message = $this->message;
                    DevWrongPasswordEventTest::$context = $this->context;
                }
            },
        ];
    }
}
