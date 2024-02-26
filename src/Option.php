<?php

namespace Thor\Maybe;

use Throwable;
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
            return Maybe::None;
        }
        return Maybe::Some;
    }

    public function is_none(): bool
    {
        return $this->is() === Maybe::None;
    }

    public function is_some(): bool
    {
        return $this->is() === Maybe::Some;
    }

    public function is_a(Maybe $maybe): bool
    {
        return match ($maybe) {
            Maybe::Some => $this->is_some(),
            Maybe::None => $this->is_none(),
        };
    }

    public function matches(callable $ifSome, callable $ifNone): mixed
    {
        if ($this->is_some()) {
            return $ifSome($this->raw);
        } else {
            return $ifNone();
        }
    }

    public function unwrap_or_else(callable $ifNone): mixed
    {
        return $this->matches(
            fn(mixed $some) => $some,
            $ifNone
        );
    }

    public function unwrap_or(mixed $default): mixed
    {
        return $this->unwrap_or_else(fn() => $default);
    }

    /**
     * @throws Throwable
     */
    public function unwrap_or_throw(Throwable $t): mixed
    {
        return $this->unwrap_or_else(fn() => throw $t);
    }

    /**
     * @throws Throwable
     */
    public function unwrap(): mixed
    {
        return $this->unwrap_or_else(fn() => throw new \RuntimeException("Option : trying to unwrap a None value."));
    }

    public static function none(): self
    {
        $option      = new self();
        $option->raw = null;
        return $option;
    }

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