<?php

declare(strict_types=1);

namespace UUA\Tests\Fixtures\DemoApp\Database;

use PDO;

trait DatabaseMainTrait
{

    use \UUA\Traits\ContainerTrait;

    public function db(): PDO
    {
        return $this->container->get(DatabaseMain::class);
    }
}
