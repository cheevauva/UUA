<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\Unit;
use UUA\Wrapper;

trait WrappingTrait
{

    use ContainerTrait;

    #[\Override]
    public function serve(): void
    {
        $prev = $this->process(...);

        foreach ($this->wrappers() as $wrapper) {
            $next = Wrapper::as($wrapper::new($this->container));
            $next->rootUnit = $this;
            $next->nextUnit = $prev;

            $prev = $next->serve(...);
        }

        if (empty($next)) {
            $this->process();
            return;
        }

        Unit::as($next)->serve();
    }

    abstract protected function process(): void;
    
    /**
     * @return array<class-string>
     */
    protected function wrappers(): array
    {
        /** @var array<class-string, array<class-string>> $wrappers */
        $wrappers = $this->container->get('wrappers');

        return $wrappers[$this->unitNameForWrapping()] ?? [];
    }

    /**
     * @return class-string
     */
    protected function unitNameForWrapping(): string
    {
        if (str_contains(get_debug_type($this), '@anonymous')) {
            // @phpstan-ignore-next-line
            return get_parent_class($this);
        }

        return $this::class;
    }
}
