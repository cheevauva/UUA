<?php

declare(strict_types=1);

namespace UUA\DemoApp\Task\DAO;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\Task\Entity\TaskEntity;
use UUA\DemoApp\Task\Mapper\TaskEntityFromRowMapper;
use UUA\DemoApp\Task\Exception\TaskEntityNotFoundException;

class TaskGetByIdDAO extends \UUA\DAO
{

    use \UUA\DemoApp\Database\DatabaseMainTrait;
    use \UUA\Traits\WrapAndServeTrait;

    public int $id;
    public protected(set) TaskEntity $task;

    #[\Override]
    public function serve(): void
    {
        $stmt = $this->db()->prepare($this->getSQL());
        $stmt->execute($this->getParams());

        /** @var array<string, bool|float|int|string|null> $row */
        $row = $stmt->fetch($this->db()::FETCH_ASSOC);

        if (empty($row)) {
            throw new TaskEntityNotFoundException;
        }

        $this->task = TaskEntityFromRowMapper::call($this->container, $row)->task;
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

    public static function call(ContainerInterface $container, int $id): self
    {
        $self = self::new($container);
        $self->id = $id;
        $self->serve();

        return $self;
    }
}
