<?php

declare(strict_types=1);

namespace UUA\DemoApp\App\Servant;

class FileLoggerServant extends \UUA\Servant
{

    use \UUA\Traits\CurrentTimeTrait;

    public string $message;
    public string $level = 'INFO';

    /**
     * @var array<mixed, mixed>
     */
    public array $context = [];
    public string $filename = 'demo.log';

    #[\Override]
    protected function run(): void
    {
        $message = sprintf("[%s] [%s] %s %s\n", $this->currentTime()->format('Y-m-d H:i:s'), $this->level, $this->message, json_encode($this->context, JSON_UNESCAPED_UNICODE));

        file_put_contents($this->filename, $message, FILE_APPEND);
    }
}
