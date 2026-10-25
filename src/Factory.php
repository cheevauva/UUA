<?php

declare(strict_types=1);

namespace UUA;

use UUA\Container\Container;
use UUA\Exception\DuplicateInstanceException;
use UUA\Exception\InvalidFactoryResultException;

class Factory implements FactoryInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $assets;

    use Traits\ContainerTrait;

    protected function init(): void
    {
        // @phpstan-ignore-next-line
        $this->assets = $this->container->get(Container::ASSETS_NAME);
    }

    #[\Override]
    public function make(string $id): object
    {
        $asset = $this->assets[$id] ?? null;

        $isClosure = $asset instanceof \Closure;
        $isClassName = !$isClosure && is_string($asset) && class_exists($asset, true);

        if ($asset && !$isClassName && !$isClosure) {
            throw new DuplicateInstanceException(sprintf('Instance for "%s" already exists. Use a Closure for lazy loading', $id));
        }

        if ($isClosure) {
            $object = ($asset)($this->container);

            if (!is_object($object)) {
                throw new InvalidFactoryResultException(sprintf('Factory closure for "%s" did not return an object.', $id));
            }

            return $object;
        }

        return new $id($this->container);
    }
}
