<?php

declare(strict_types=1);

namespace UUA\Tests\Traits;

use UUA\DTO;
use UUA\Exception\ClassMismatchException;

class DevAsTraitTest extends \PHPUnit\Framework\TestCase
{

    public function testAsComplete(): void
    {
        self::assertInstanceOf(DTO::class, DTO::as(new class() extends DTO {
            
        }));
    }

    public function testAsButNullPass(): void
    {
        $this->expectException(ClassMismatchException::class);
        $this->expectExceptionMessageMatches(sprintf('/^Expected %s, but received null\. Called in %s \(\d+\)$/', preg_quote(DTO::class), preg_quote(__FILE__, '/')));

        DTO::as(null);
    }

    public function testAsButStdClassPass(): void
    {
        $this->expectException(ClassMismatchException::class);
        $this->expectExceptionMessageMatches(sprintf('/^Expected %s, but received %s\. Called in %s \(\d+\)$/', preg_quote(DTO::class), preg_quote(\stdClass::class), preg_quote(__FILE__, '/')));

        DTO::as(new \stdClass());
    }
}
