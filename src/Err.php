<?php

declare(strict_types=1);

namespace JustSteveKing\Result;

use JustSteveKing\Result\Contracts\ResultInterface;
use JustSteveKing\Result\Exceptions\UnwrapException;
use Throwable;

/**
 * Error variant of Result.
 * @implements ResultInterface<mixed>
 */
final class Err implements ResultInterface
{
    public function __construct(
        private readonly Throwable $error,
    ) {}

    public function isOk(): bool
    {
        return false;
    }

    public function isErr(): bool
    {
        return true;
    }

    /**
     * @return mixed
     * @throws UnwrapException
     */
    public function unwrap(): mixed
    {
        throw UnwrapException::becauseErr('Tried to unwrap an Err result.', $this->error);
    }

    /**
     * @return mixed
     * @throws UnwrapException
     */
    public function expect(string $message): mixed
    {
        throw UnwrapException::becauseErr($message, $this->error);
    }

    /**
     * @return Throwable
     */
    public function error(): Throwable
    {
        return $this->error;
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function valueOr(mixed $default): mixed
    {
        return $default;
    }

    /**
     * @template S
     * @param callable(mixed): S $fn
     * @return ResultInterface<S>
     */
    public function map(callable $fn): ResultInterface
    {
        return $this;
    }

    /**
        * @param callable(Throwable): Throwable $fn
         * @return ResultInterface<mixed>
        */
    public function mapErr(callable $fn): ResultInterface
    {
        return new self($fn($this->error));
    }

    /**
     * @template S
     * @param callable(mixed): ResultInterface<S> $fn
     * @return ResultInterface<S>
     */
    public function andThen(callable $fn): ResultInterface
    {
        return $this;
    }

    /**
     * @param callable(Throwable): ResultInterface<mixed> $fn
     * @return ResultInterface<mixed>
     */
    public function orElse(callable $fn): ResultInterface
    {
        return $fn($this->error);
    }

    /**
     * @param callable(mixed): void $fn
     * @return $this
     */
    public function tap(callable $fn): self
    {
        return $this;
    }

    /**
     * @param callable(Throwable): void $fn
     * @return $this
     */
    public function tapErr(callable $fn): self
    {
        $fn($this->error);

        return $this;
    }
}

