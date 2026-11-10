<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\DAO;

use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\DemoApp\Task\Mapper\TaskEntityFromRowMapper;
use UUA\DemoApp\Task\Exception\TaskEntityNotFoundException;

class TaskGetByIdDAO extends \UUA\DAO
{

    use \UUA\DemoApp\App\Database\DatabaseMainTrait;
    use \UUA\Traits\WrappingTrait;
    use \UUA\Traits\CallWithIdTrait;

    public protected(set) TaskEntity $task;

    #[\Override]
    protected function run(): void
    {
        $stmt = $this->db()->prepare($this->getSQL());
        $stmt->execute($this->getParams());

        /** @var array<string, bool|float|int|string|null> $row */
        $row = $stmt->fetch($this->db()::FETCH_ASSOC);

        if (empty($row)) {
            throw new TaskEntityNotFoundException;
        }

        $this->task = TaskEntityFromRowMapper::call($this, $row)->task;
    }

    protected function getSQL(): string
    {
        return 'SELECT * FROM tasks WHERE id = :id';
    }

    /**
     * @return array<string, int>
     */
    protected function getParams(): array
    {
        return [
            'id' => $this->id,
        ];
    }

}
