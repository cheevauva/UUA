<?php

declare(strict_types=1);

namespace UUA\DemoApp\User\DAO;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\User\Entity\UserEntity;
use UUA\DemoApp\User\Mapper\UserEntityFromRowMapper;
use UUA\DemoApp\User\Exception\UserEntityNotFoundException;

class UserGetByIdDAO extends \UUA\DAO
{

    use \UUA\DemoApp\App\Database\DatabaseMainTrait;
    use \UUA\Traits\WrapAndServeTrait;

    public int $id;
    public protected(set) UserEntity $user;

    #[\Override]
    public function serve(): void
    {
        $stmt = $this->db()->prepare($this->getSQL());
        $stmt->execute($this->getParams());

        /** @var array<string, bool|float|int|string|null> $row */
        $row = $stmt->fetch($this->db()::FETCH_ASSOC);

        if (empty($row)) {
            throw new UserEntityNotFoundException;
        }

        $this->user = UserEntityFromRowMapper::call($this->container, $row)->user;
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
