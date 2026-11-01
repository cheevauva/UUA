<?php

declare(strict_types=1);

namespace UUA\DemoApp\App\Database;

use PDO;

trait DatabaseMainTrait
{

    use \UUA\Traits\ContainerTrait;

    public function db(): PDO
    {
        return DatabaseMain::singleton($this->container);
    }
}
