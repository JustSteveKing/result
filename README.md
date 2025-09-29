# Result

A tiny, framework-agnostic Result type for PHP that makes error handling explicit, composable, and testable.

- Express success as Ok(value) and failure as Err(Throwable)
- Eliminate nullable return values and ambiguous sentinel values
- Chain operations with map/andThen and recover with mapErr/orElse
- Ergonomic helpers and a small ComparableResult utility


## Requirements

- PHP 8.4+


## Installation

```bash
composer require juststeveking/result
```


## Quick start

```php
<?php

use JustSteveKing\Result\Result;
use function JustSteveKing\Result\{ok, err, result_match};

// Via the static factory
$r1 = Result::ok('hello');
$r2 = Result::err(new RuntimeException('nope'));

// Or via helper functions (autoloaded)
$r3 = ok(21)->map(fn (int $n) => $n * 2); // Ok(42)

// Branching with pattern matching helper
$out = result_match(
	$r3,
	fn (int $n) => "answer: $n",
	fn (Throwable $e) => 'error: ' . $e->getMessage(),
);
// "answer: 42"
```


## The Result interface

All results implement `JustSteveKing\Result\Contracts\ResultInterface<T>` where `T` is the success value type (documented via PHPDoc for static analysis).

Key operations:

- `isOk(): bool`
- `isErr(): bool`
- `unwrap(): T` - returns the value, or throws UnwrapException on Err
- `expect(string $message): T` - like `unwrap()`, but with your message
- `error(): ?Throwable` - returns the error on Err, null on Ok
- `valueOr(T $default): T` - returns the value or a default on error
- `map(callable(T): S): ResultInterface<S>` — transform an Ok value; no-op on Err
- `mapErr(callable(Throwable): Throwable): ResultInterface<T>` - transform the error; no-op on Ok
- `andThen(callable(T): ResultInterface<S>): ResultInterface<S>` - flat-map for chaining operations that return Result
- `orElse(callable(Throwable): ResultInterface<T>): ResultInterface<T>` - recover from Err by producing a new Result
- `tap(callable(T): void): $this` - side-effect on Ok; no-op on Err
- `tapErr(callable(Throwable): void): $this` - side-effect on Err; no-op on Ok


### Examples

Transforming and chaining:

```php
use JustSteveKing\Result\Result;
use JustSteveKing\Result\Contracts\ResultInterface;

function parseInt(string $raw): ResultInterface {
	return is_numeric($raw)
		? Result::ok((int) $raw)
		: Result::err(new InvalidArgumentException('not a number'));
}

$res = parseInt('10')
	->map(fn (int $n) => $n * 2)               // Ok(20)
	->andThen(fn (int $n) => Result::ok($n+1)) // Ok(21)
;

$value = $res->valueOr(0); // 21
```

Recovering from errors:

```php
$res = Result::err(new RuntimeException('boom'))
	->mapErr(fn (Throwable $e) => new LogicException('mapped', previous: $e))
	->orElse(fn (Throwable $e) => Result::ok('default'));

// $res is Ok('default')
```

Avoiding exceptions at call sites:

```php
try {
	$value = mightThrow();
	$result = Result::ok($value);
} catch (Throwable $e) {
	$result = Result::err($e);
}

$safe = $result->valueOr('fallback');
```

Tapping for side-effects:

```php
ok(['id' => 1])
	->tap(fn (array $data) => error_log('created: ' . $data['id']))
	->tapErr(fn (Throwable $e) => error_log('failed: ' . $e->getMessage()));
```

Unwrapping (will throw on Err):

```php
$value = Result::ok('x')->unwrap(); // 'x'
Result::err(new RuntimeException('no'))
	->expect('Failed to compute');   // throws UnwrapException
```


## Helper functions

This package autoloads a few global helpers in the `JustSteveKing\Result` namespace:

- ok(mixed $value): ResultInterface<T>
- err(Throwable $error): ResultInterface<mixed>
- result_match(ResultInterface $result, callable $onOk, callable $onErr): mixed

Example:

```php
use function JustSteveKing\Result\{ok, result_match};

$output = result_match(
	ok('a'),
	fn (string $v) => $v . 'b',
	fn (Throwable $e) => 'error',
);
```


## ComparableResult

`ComparableResult` is a small utility for success values that are comparable as array-keys (`int|string`). It's handy when you need a simple value equality check without unwrapping:

```php
use JustSteveKing\Result\ComparableResult;

$cmp = ComparableResult::ok('foo');
$cmp->equals('foo'); // true
$cmp->equals('bar'); // false

$err = ComparableResult::err(new RuntimeException('nope'));
$err->equals('anything'); // false

// Access the wrapped Result if you need it
$inner = $cmp->inner(); // ResultInterface<int|string>
```


## Error behavior

Calling `unwrap()` or `expect()` on an `Err` throws `JustSteveKing\Result\Exceptions\UnwrapException` with your message (for `expect`) and the original Throwable as `previous`.


## Static analysis and generics

The library uses PHPDoc templates (e.g. `@template T`) to communicate types to tools like PHPStan/Psalm. You'll get strong typing for `ResultInterface<T>` in editors and CI when using these tools.


## Tooling

Run the test suite:

```bash
composer test
```

Static analysis and formatting:

```bash
composer stan
composer pint
```


## Contributing

Bug reports and PRs are welcome. See `CONTRIBUTING.md` for guidelines.


## License

MIT. See `LICENSE`.

