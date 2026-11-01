<?php

declare(strict_types=1);

namespace UUA\Servant;

use UUA\Unit;
use UUA\Servant;
use UUA\Wrapper;

class WrapAndServeServant extends Servant
{

    public object $caller;
    public Unit $unit;

    /**
     * @var array<string, array<string, array<int, string>>>
     */
    protected array $wrappers;

    #[\Override]
    protected function init(): void
    {
        // @phpstan-ignore-next-line
        $this->wrappers = $this->container->get('wrappers');
    }

    #[\Override]
    public function serve(): void
    {
        $unit = $this->unit;
        $caller = $this->caller;
        $unitName = $unit::class;

        if (str_contains(get_debug_type($unit), '@anonymous')) {
            $unitName = get_parent_class($unit);
        }

        $wrappers = [
            ...$this->wrappers[$unitName]['any'] ?? [],
            ...$this->wrappers[$unitName][$caller::class] ?? [],
        ];

        $prev = $unit;

        foreach ($wrappers as $wrapper) {
            $next = Wrapper::as($wrapper::new($this->container));
            $next->rootUnit = $unit;
            $next->nextUnit = $prev;

            $prev = $next;
        }

        Unit::as($next ?? $unit)->serve();
    }
}
