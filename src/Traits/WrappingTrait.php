<?php

declare(strict_types=1);

namespace UUA\Traits;

use UUA\Wrapper;

trait WrappingTrait
{

    use ContainerTrait;

    #[\Override]
    public function serve(): void
    {
        $prev = $this->run(...);

        foreach ($this->wrappers() as $wrapper) {
            $next = Wrapper::as($wrapper::new($this->container));
            $next->rootUnit = $this;
            $next->nextUnit = $prev;

            $prev = $next->serve(...);
        }

        if (empty($next)) {
            $this->run();
        } else {
            $next->serve();
        }
    }

    abstract protected function run(): void;

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
        $unitName = $this::class;

        if (str_contains($unitName, '@anonymous')) {
            $unitName = get_parent_class($unitName);
        }
        
        // @phpstan-ignore-next-line
        return $unitName;
    }
}
