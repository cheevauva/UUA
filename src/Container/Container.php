<?php

declare(strict_types=1);

namespace UUA\Container;

use UUA\Container\Exception\ContainerNotFoundException;
use UUA\FactoryInterface;
use UUA\Factory;

class Container implements \Psr\Container\ContainerInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $assetsResolved = [];

    /**
     * @var array<string, mixed>
     */
    protected array $assets = [];

    public const ASSETS_NAME = '__assets8401ysnl';

    public function __construct(\Closure $assets)
    {
        $this->assetsResolved[self::ASSETS_NAME] = $this->assets = $assets($this);
        $this->assetsResolved[FactoryInterface::class] = $this->assets[FactoryInterface::class] ?? new Factory($this);
    }

    #[\Override]
    public function get(string $id): mixed
    {
        if (!isset($this->assetsResolved[$id])) {
            if (class_exists($id, true) || interface_exists($id, true)) {
                $this->assetsResolved[$id] = $this->factory()->make($id);
            } else {
                $this->assetsResolved[$id] = $this->assets[$id] ?? throw new ContainerNotFoundException($id);
            }
        }

        if ($this->assetsResolved[$id] instanceof \Closure) {
            return $this->assetsResolved[$id]($this);
        }

        return $this->assetsResolved[$id];
    }

    protected function factory(): FactoryInterface
    {
        // @phpstan-ignore-next-line
        return $this->get(FactoryInterface::class);
    }

    #[\Override]
    public function has(string $id): bool
    {
        return isset($this->assetsResolved[$id]) || isset($this->assets[$id]);
    }
}
