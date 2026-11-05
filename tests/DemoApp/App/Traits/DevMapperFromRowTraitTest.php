<?php

declare(strict_types=1);

namespace UUA\Tests\App\Traits;

use UUA\Mapper;
use UUA\DemoApp\App\Traits\MapperFromRowTrait;

class DevMapperFromRowTraitTest extends \UUA\Tests\DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        $mapper = $this->mapper();
        $mapper->row = [
            'payload' => json_encode(['abc']),
            'enabled' => 1,
            'date_created' => '2020-02-02 02:02:02',
        ];
        $mapper->serve();

        self::assertEquals(['abc'], $mapper->payload);
        self::assertEquals(true, $mapper->enabled);
        self::assertEquals(new \DateTimeImmutable('2020-02-02 02:02:02'), $mapper->dateCreated);
    }

    public function testColumnNotFound(): void
    {
        $this->expectExceptionMessageIs('row.payload is undefined');

        $mapper = $this->mapper();
        $mapper->row = [];
        $mapper->serve();
    }

    public function testJsonNotString(): void
    {
        $this->expectExceptionMessageIs('row.payload is not string');

        $mapper = $this->mapper();
        $mapper->row = [
            'payload' => 1,
        ];
        $mapper->serve();
    }

    // @phpstan-ignore missingType.return
    protected function mapper()
    {
        return new class($this->container) extends Mapper {

            use MapperFromRowTrait;

            /**
             * @var array<mixed, mixed>
             */
            public array $payload;
            public bool $enabled;
            public ?\DateTimeInterface $dateCreated;

            #[\Override]
            public function serve(): void
            {
                $this->payload = $this->json('payload');
                $this->enabled = $this->bool('enabled');
                $this->dateCreated = $this->dateTimeOrNull('date_created');
            }
        };
    }
}
