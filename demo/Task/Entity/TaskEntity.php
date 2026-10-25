<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Entity;

class TaskEntity
{

    public ?int $id;
    public string $description;
    public \DateTimeImmutable $dateCreated;
    public \DateTimeImmutable $dateUpdated;

    public function __construct()
    {
        $this->description = 'Empty description';
        $this->dateCreated = new \DateTimeImmutable;
        $this->dateUpdated = new \DateTimeImmutable;
    }
}
