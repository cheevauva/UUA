<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\Mapper;

class TaskEntityToRowMapper extends \UUA\Mapper
{

    use \UUA\DemoApp\Task\Traits\TaskCallTrait;

    /**
     * @var array<string, mixed>
     */
    public array $row;

    #[\Override]
    public function serve(): void
    {
        $this->row = [
            'description' => $this->task->description,
            'date_created' => $this->task->dateCreated->format('Y-m-d H:i:s'),
            'date_updated' => $this->task->dateUpdated->format('Y-m-d H:i:s'),
        ];

        if (isset($this->task->id)) {
            $this->row['id'] = $this->task->id;
        }
    }
}
