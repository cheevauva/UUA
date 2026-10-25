<?php

declare(strict_types=1);

namespace UUA\DemoApp\Traits;

use Psr\Container\ContainerInterface;

trait MapperFromRowTrait
{

    /**
     * @var array<string, string|int|float|null|bool>
     */
    public array $row;

    protected function string(string $key): string
    {
        return strval($this->row[$key] ?? throw $this->notFoundKeyInRowException($key));
    }

    protected function dateTime(string $key): \DateTimeImmutable
    {
        return new \DateTimeImmutable(strval($this->row[$key] ?? throw $this->notFoundKeyInRowException($key)));
    }

    protected function dateTimeOrNull(string $key): ?\DateTimeImmutable
    {
        return isset($this->row[$key]) ? new \DateTimeImmutable(strval($this->row[$key])) : null;
    }

    protected function bool(string $key): bool
    {
        return boolval($this->row[$key] ?? throw $this->notFoundKeyInRowException($key));
    }

    protected function int(string $key): int
    {
        return intval($this->row[$key] ?? throw $this->notFoundKeyInRowException($key));
    }

    /**
     * @param string $key
     * @return array<mixed, mixed>|array<mixed>
     */
    protected function json(string $key): array
    {
        $this->row[$key] ?? throw $this->notFoundKeyInRowException($key);

        if (!is_string($this->row[$key])) {
            throw new \Exception(sprintf('row.%s is not string', $key));
        }

        // @phpstan-ignore-next-line
        return json_decode($this->row[$key], true);
    }

    protected function notFoundKeyInRowException(string $key): \Throwable
    {
        throw new \Exception(sprintf('row.%s is undefined', $key));
    }

    /**
     * @param ContainerInterface $container
     * @param array<string, string|int|float|null|bool> $row
     * @return self
     */
    public static function call(ContainerInterface $container, array $row): self
    {
        $self = self::new($container);
        $self->row = $row;
        $self->serve();

        return $self;
    }
}
