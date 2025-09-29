# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/) and the format is inspired by [Keep a Changelog](https://keepachangelog.com/).

## [1.0.0] - 2025-09-29

### Added

- Core Result type with factories:
	- `JustSteveKing\Result\Result::ok(mixed $value)`
	- `JustSteveKing\Result\Result::err(Throwable $error)`
- Success and error variants implementing `ResultInterface<T>`:
	- `JustSteveKing\Result\Ok`
	- `JustSteveKing\Result\Err`
- Result operations on `ResultInterface<T>`:
	- `isOk()`, `isErr()`
	- `unwrap()`, `expect(string $message)`
	- `error(): ?Throwable`, `valueOr(mixed $default)`
	- `map(callable $fn)`, `mapErr(callable $fn)`
	- `andThen(callable $fn)`, `orElse(callable $fn)`
	- `tap(callable $fn)`, `tapErr(callable $fn)`
- Global helper functions (autoloaded):
	- `JustSteveKing\Result\ok(mixed $value)`
	- `JustSteveKing\Result\err(Throwable $error)`
	- `JustSteveKing\Result\result_match(ResultInterface $result, callable $onOk, callable $onErr): mixed`
- `ComparableResult` utility for equality checks on `int|string` Ok values.
- `Exceptions\UnwrapException` thrown when unwrapping `Err` variants.
- PHPDoc generics for strong static analysis with tools like PHPStan/Psalm.

### Tooling & Docs

- Composer scripts: `test`, `stan`, `pint`, `lint`.
- PHPUnit test suite.
- PHPStan (with strict rules) and Laravel Pint for formatting.
- Initial README with quickstart and API overview.



