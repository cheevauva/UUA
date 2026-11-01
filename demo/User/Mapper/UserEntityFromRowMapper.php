<?php

declare(strict_types=1);

namespace UUA\DemoApp\User\Mapper;

use UUA\DemoApp\User\Entity\UserEntity;

class UserEntityFromRowMapper extends \UUA\Mapper
{

    use \UUA\DemoApp\App\Traits\MapperFromRowTrait;

    public UserEntity $user;

    #[\Override]
    public function serve(): void
    {
        $task = $this->user = new UserEntity;
        $task->id = $this->int('id');
        $task->name = $this->string('name');
    }
}
