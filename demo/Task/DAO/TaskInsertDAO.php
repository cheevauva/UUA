<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\DAO;

use UUA\DemoApp\Task\Mapper\TaskEntityToRowMapper;

class TaskInsertDAO extends \UUA\DAO
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

        $this->task->id = (int) $this->db()->lastInsertId();
    }

    protected function getSQL(): string
    {
        return sprintf('INSERT INTO tasks (%s) VALUES (:%s)', implode(', ', array_keys($this->row)), implode(', :', array_keys($this->row)));
    }

    /**
     * @return array<string, mixed> $row
     */
    protected function getParams(): array
    {
        return $this->row;
    }
}
