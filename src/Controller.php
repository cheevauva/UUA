<?php

declare(strict_types=1);

namespace UUA;

use SplObjectStorage;

abstract class Controller extends Unit implements ObjectInteractionInterface
{

    use \UUA\Traits\EventDispatcherTrait;
    use \UUA\Traits\EnvTrait;
    use \UUA\Traits\WrapAndServeTrait;

    /**
     * @var SplObjectStorage<\Throwable|object, mixed>
     */
    protected SplObjectStorage $messages;

    /**
     * @var SplObjectStorage<\Throwable, mixed>
     */
    protected SplObjectStorage $exceptions;

    protected function catch(\Throwable $object): void
    {
        
    }

    /**
     * @return class-string[]
     */
    abstract protected function domainUnits(): array;

    /**
     * @return class-string[]
     */
    abstract protected function viewUnits(): array;

    #[\Override]
    public function serve(): void
    {
        $this->messages = new SplObjectStorage();
        $this->exceptions = new SplObjectStorage();

        $domainUnits = $this->overrideDomainUnits($this->domainUnits());
        $viewUnits = $this->overrideViewUnits($this->viewUnits());

        try {
            $this->runUnits($domainUnits, $this->stopRunningDomainUnits(...));
        } catch (\Throwable $ex) {
            $this->exceptions->attach($ex);
            $this->catch($ex);
        }

        $this->throwUncatchedExceptions();
        $this->runUnits($viewUnits, $this->stopRunningViewUnits(...));
    }

    /**
     * @param class-string[] $domainUnits
     * @return class-string[]
     */
    protected function overrideDomainUnits(array $domainUnits): array
    {
        return $domainUnits;
    }

    /**
     * @param class-string[] $viewUnits
     * @return class-string[]
     */
    protected function overrideViewUnits(array $viewUnits): array
    {
        return $viewUnits;
    }

    protected function throwUncatchedExceptions(): void
    {
        if (!$this->exceptions->valid()) {
            return;
        }

        while ($this->exceptions->valid()) {
            $uncatchedMessage = $this->exceptions->current();

            if (!$this->messages->contains($uncatchedMessage)) {
                throw $uncatchedMessage;
            }

            $this->exceptions->next();
        }
    }

    protected function stopRunningDomainUnits(): bool
    {
        return $this->messages->count() !== 0;
    }

    protected function stopRunningViewUnits(): bool
    {
        return false;
    }

    /**
     * @param array<Unit>|array<int, class-string> $units
     * @return void
     */
    protected function runUnits(array $units, \Closure $stopRunningUnits): void
    {
        foreach ($units as $unit) {
            if (($stopRunningUnits)()) {
                return;
            }

            if (is_string($unit)) {
                $unit = $unit::new($this->container);
            }

            if (!($unit instanceof Unit)) {
                throw new \Exception(sprintf('%s должен быть наследником %s', get_class($unit), Unit::class));
            }

            $this->to($unit);
            $this->wrapAndServe($unit);
            $this->from($unit);
        }
    }
}
