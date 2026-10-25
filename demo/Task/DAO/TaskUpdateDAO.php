<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\DAO;

use UUA\DemoApp\Task\Mapper\TaskEntityToRowMapper;

class TaskUpdateDAO extends \UUA\DAO
{

    use \UUA\DemoApp\Database\DatabaseMainTrait;
    use \UUA\DemoApp\Task\Traits\TaskCallTrait;

    /**
     * @var array<string, mixed>
     */
    protected array $row;

    #[\Override]
    public function serve(): void
    {
        $this->row = TaskEntityToRowMapper::call($this->container, $this->task)->row;
        //
        $stmt = $this->db()->prepare($this->getSQL());
        $stmt->execute($this->getParams());
    }

    protected function getSQL(): string
    {
        $columns = implode(', ', array_map(fn(string $column) => sprintf('%s = :%s', $column, $column), array_keys($this->row)));

        return sprintf('UPDATE tasks SET %s WHERE id = :id', $columns);
    }

    /**
     * @return array<string, mixed> $row
     */
    protected function getParams(): array
    {
        return $this->row;
    }
}
