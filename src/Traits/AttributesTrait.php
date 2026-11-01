<?php

declare(strict_types=1);

namespace UUA\Traits;

trait AttributesTrait
{

    /** @var array<string, mixed> */
    protected array $attributes = [];

    public function withAttr(string $attr, mixed $value): void
    {
        $this->attributes[$attr] = $value;
    }

    /**
     * @param string $attr
     * @return mixed
     */
    public function attr(string $attr): mixed
    {
        return $this->attributes[$attr] ?? null;
    }
}
