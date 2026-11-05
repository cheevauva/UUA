<?php

declare(strict_types=1);

namespace UUA\DemoApp\User\Mapper;

use UUA\DemoApp\User\Entity\UserEntity;
use UUA\DemoApp\User\Mapper\UserEntityFromRowMapper;

class DevUserEntityFromRowMapperTest extends \UUA\Tests\DevUnitTestCase
{

    #[\Override]
    public function testMain(): void
    {
        $user = new UserEntity;
        $user->id = 1;
        $user->name = 'Name';

        self::assertEquals($user, UserEntityFromRowMapper::call($this->container, [
            'id' => 1,
            'name' => 'Name',
        ])->user);
    }
}
