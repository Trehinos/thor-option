<?php

namespace Thor\Maybe;

use Throwable;
use RuntimeException;
use InvalidArgumentException;

/**
 * ### Option class
 *
 * Describe an object that can be either contain no value (`isNone()`) or a value (`isSome()`).
 *
 * ### Example
 *
 * ```
 * use Thor\Maybe\Option;
 * use Thor\Maybe\Maybe;
 *
 * $myOption = Option::from("data...");
 * // $myOption->isA(Maybe::SOME) === true
 * echo $myOption->unwrapOr('');
 * ```
 */
class Option
{

    private mixed $raw = null;

    private function __construct()
    {
    }

    /**
     * Returns the nature of the Option. The function can return :
     *
     *  - a `Maybe::NONE` (if the Option contains no data),
     *  - or a `Maybe::SOME` (if the Option contains some data).
     */
    public function is(): Maybe
    {
        if ($this->raw === null) {
            return Maybe::NONE;
        }
        return Maybe::SOME;
    }

    /**
     * This function return `true` if the Option contains no value.
     */
    public function isNone(): bool
    {
        return $this->is() === Maybe::NONE;
    }

    /**
     * This function return `false` if the Option contains a value.
     */
    public function isSome(): bool
    {
        return $this->is() === Maybe::SOME;
    }

    /**
     * Returns true if the Option corresponds the nature described by the specified `Maybe`.
     */
    public function isA(Maybe $maybe): bool
    {
        return match ($maybe) {
            Maybe::SOME => $this->isSome(),
            Maybe::NONE => $this->isNone(),
        };
    }

    /**
     * Call the function $ifSome if the Option contains a value or call the
     * function $ifNone if not.
     *
     * The function `matches` returns the value returned by the called function.
     */
    public function matches(callable $ifSome, callable $ifNone): mixed
    {
        if ($this->isSome()) {
            return $ifSome($this->raw);
        } else {
            return $ifNone();
        }
    }

    /**
     * Returns the contained value if the option is SOME or else call the function `$ifNone` and returns its returned value.
     */
    public function unwrapOrElse(callable $ifNone): mixed
    {
        return $this->matches(
            fn(mixed $some) => $some,
            $ifNone
        );
    }

    /**
     * Returns the contained value if the option is SOME or else returns `$default`.
     */
    public function unwrapOr(mixed $default): mixed
    {
        return $this->unwrapOrElse(fn() => $default);
    }

    /**
     * Returns the contained value if the option is SOME or else throws the specified `Throwable`.
     *
     * @throws Throwable
     */
    public function unwrapOrThrow(Throwable $t): mixed
    {
        return $this->unwrapOrElse(fn() => throw $t);
    }

    /**
     * Returns the contained value if the option is SOME or else throws a `RuntimeException`.
     *
     * @throws RuntimeException
     */
    public function unwrap(): mixed
    {
        return $this->unwrapOrElse(fn() => throw new RuntimeException("Option : trying to unwrap a None value."));
    }

    /**
     * Creates a new `Option` with no value.
     */
    public static function none(): self
    {
        $option      = new self();
        $option->raw = null;
        return $option;
    }

    /**
     * Creates a new `Option` with the specified value. The value can not be `null`.
     *
     * @throws InvalidArgumentException if $data is null.
     */
    public static function some(mixed $data): self
    {
        if ($data === null) {
            throw new InvalidArgumentException('Cannot create an Option::some() from a null value.');
        }
        $option      = new self();
        $option->raw = $data;
        return $option;
    }

    /**
     * Creates an `Option` according to the specified value.
     *
     * This function calls `Option::some($data)` if $data is not `null` or else it calls `Option::none()`.
     */
    public static function from(mixed $data): self
    {
        if ($data === null) {
            return self::none();
        }
        return self::some($data);
    }

}