<?php

declare(strict_types=1);

namespace UUA\DemoApp\User\Entity;

class UserEntity
{

    use \UUA\Traits\AsTrait;
    use \UUA\Traits\AttributesTrait;

    public ?int $id;
    public string $name;
}
