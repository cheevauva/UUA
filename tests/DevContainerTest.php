<?php

declare(strict_types=1);

namespace UUA\Tests;

use UUA\Container\Exception\ContainerNotFoundException;

class DevContainerTest extends DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        $this->expectException(ContainerNotFoundException::class);
        $this->expectExceptionMessageIs('Не найдены данные в контейнере по ключу aabbcc');
        
        self::assertFalse($this->container->has('aabbcc'));
        
        $this->container->get('aabbcc');
    }
}
