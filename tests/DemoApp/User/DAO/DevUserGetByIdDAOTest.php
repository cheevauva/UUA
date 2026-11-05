<?php

declare(strict_types=1);

namespace UUA\Tests\DemoApp\User\DAO;

use Psr\Container\ContainerInterface;
use UUA\DemoApp\User\Entity\UserEntity;
use UUA\DemoApp\User\Mapper\UserEntityFromRowMapper;
use UUA\DemoApp\User\Exception\UserEntityNotFoundException;
use UUA\DemoApp\User\DAO\UserGetByIdDAO;

class DevUserGetByIdDAOTest extends \UUA\Tests\DevUnitTestCase
{

    /**
     * @var null|array<string, mixed>
     */
    public static ?array $params;
    public static ?string $sql;
    public static ?UserEntityFromRowMapper $mapper;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        self::$sql = null;
        self::$params = null;
        self::$mapper = null;
    }

    #[\Override]
    public function testMain(): void
    {
        $user = UserGetByIdDAO::call($this->container, 1)->user;

        self::assertEquals('SELECT * FROM users WHERE id = :id', self::$sql);
        self::assertEquals([
            'id' => 1,
        ], self::$params);
        self::assertEquals(1, $user->id);
    }

    public function testNotFound(): void
    {
        $this->expectException(UserEntityNotFoundException::class);

        UserGetByIdDAO::call($this->container, 2)->user;
    }

    #[\Override]
    protected function mocks(ContainerInterface $c): array
    {
        return [
            UserGetByIdDAO::class => fn() => new class($c) extends UserGetByIdDAO {

                #[\Override]
                protected function getSQL(): string
                {
                    DevUserGetByIdDAOTest::$sql = parent::getSQL();

                    if ($this->id === 2) {
                        return 'SELECT 1 WHERE 1 = 0';
                    }

                    return 'SELECT 1 AS id';
                }

                #[\Override]
                protected function getParams(): array
                {
                    DevUserGetByIdDAOTest::$params = parent::getParams();

                    return [];
                }
            },
            UserEntityFromRowMapper::class => fn() => new class($c) extends UserEntityFromRowMapper {

                #[\Override]
                public function serve(): void
                {
                    DevUserGetByIdDAOTest::$mapper = $this;

                    $user = $this->user = new UserEntity;
                    $user->id = $this->int('id');
                }
            },
        ];
    }
}
