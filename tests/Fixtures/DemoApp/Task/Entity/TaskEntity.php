<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Task\Entity;

class TaskEntity
{

    public ?int $id;
    public string $description;
    public \DateTimeImmutable $dateCreated;
    public \DateTimeImmutable $dateUpdated;
}
