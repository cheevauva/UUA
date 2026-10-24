<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Task\Mapper;

class TaskEntityToRowMapper extends \UUA\Mapper
{

    use \UUA\Tests\Fixtures\DemoApp\Task\Traits\TaskCallTrait;

    /**
     * @var array<string, mixed>
     */
    public array $row;

    #[\Override]
    public function serve(): void
    {
        $this->row = [
            'id' => $this->task->id,
            'description' => $this->task->description,
            'date_created' => $this->task->dateCreated->format('Y-m-d H:i:s'),
            'date_updated' => $this->task->dateUpdated->format('Y-m-d H:i:s'),
        ];
    }
}
