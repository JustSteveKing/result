<?php

declare(strict_types=1);

namespace JustSteveKing\Result;

use JustSteveKing\Result\Contracts\ResultInterface;
use Throwable;

/**
 * @template T
 * @param T $value
 * @return ResultInterface<T>
 */
function ok(mixed $value): ResultInterface
{
    /** @var ResultInterface<T> $res */
    $res = Result::ok($value);
    return $res;
}

/**
 * @param Throwable $error
 * @return ResultInterface<mixed>
 */
function err(Throwable $error): ResultInterface
{
    /** @var ResultInterface<mixed> $res */
    $res = Result::err($error);
    return $res;
}

/**
 * @template T
 * @template R
 * @param ResultInterface<T> $result
 * @param callable(T): R $onOk
 * @param callable(Throwable): R $onErr
 * @return R
 */
function result_match(ResultInterface $result, callable $onOk, callable $onErr): mixed
{
    if ($result->isOk()) {
        return $onOk($result->unwrap());
    }

    // error() may be nullable in interface, but Err always returns non-null.
    $error = $result->error();
    if ($error instanceof Throwable) {
        return $onErr($error);
    }

    // Unreachable in practice: contract requires Err to contain Throwable
    throw new \LogicException('Result::error() returned null for an Err');
}

