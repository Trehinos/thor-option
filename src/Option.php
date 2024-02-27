<?php

namespace Thor\Maybe;

use Throwable;
use RuntimeException;
use InvalidArgumentException;

class Option
{

    private mixed $raw = null;

    private function __construct()
    {
    }

    public function is(): Maybe
    {
        if ($this->raw === null) {
            return Maybe::NONE;
        }
        return Maybe::SOME;
    }

    public function isNone(): bool
    {
        return $this->is() === Maybe::NONE;
    }

    public function isSome(): bool
    {
        return $this->is() === Maybe::SOME;
    }

    public function isA(Maybe $maybe): bool
    {
        return match ($maybe) {
            Maybe::SOME => $this->isSome(),
            Maybe::NONE => $this->isNone(),
        };
    }

    public function matches(callable $ifSome, callable $ifNone): mixed
    {
        if ($this->isSome()) {
            return $ifSome($this->raw);
        } else {
            return $ifNone();
        }
    }

    public function unwrapOrElse(callable $ifNone): mixed
    {
        return $this->matches(
            fn(mixed $some) => $some,
            $ifNone
        );
    }

    public function unwrapOr(mixed $default): mixed
    {
        return $this->unwrapOrElse(fn() => $default);
    }

    /**
     * @throws Throwable
     */
    public function unwrapOrThrow(Throwable $t): mixed
    {
        return $this->unwrapOrElse(fn() => throw $t);
    }

    /**
     * @throws RuntimeException
     */
    public function unwrap(): mixed
    {
        return $this->unwrapOrElse(fn() => throw new RuntimeException("Option : trying to unwrap a None value."));
    }

    public static function none(): self
    {
        $option      = new self();
        $option->raw = null;
        return $option;
    }

    /**
     * @param mixed $data
     *
     * @return self
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

    public static function from(mixed $data): self
    {
        if ($data === null) {
            return self::none();
        }
        return self::some($data);
    }

}